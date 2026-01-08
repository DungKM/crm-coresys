<?php

namespace Webkul\EmailExtended\Console\Commands;

use Illuminate\Console\Command;
use Webklex\PHPIMAP\ClientManager;
use Webkul\Email\Repositories\EmailRepository;
use Webkul\EmailExtended\Repositories\EmailThreadRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchGmailReplies extends Command
{
    protected $signature = 'email:fetch-gmail';
    protected $description = 'Lấy thư trả lời từ Gmail qua IMAP.';

    public function __construct(
        protected EmailRepository $emailRepository,
        protected EmailThreadRepository $emailThreadRepository
    ) {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $this->info('Connecting to Gmail IMAP...');
            
            $client = $this->createImapClient();
            $client->connect();

            $this->info('Connected! Fetching unread emails...');

            $messages = $this->fetchUnreadEmails($client);
            $this->info("Found {$messages->count()} unread emails");

            $this->processMessages($messages);

        } catch (\Exception $e) {
            $this->error("Connection failed: " . $e->getMessage());
            Log::error('IMAP connection failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function createImapClient()
    {
        $cm = new ClientManager();
        
        return $cm->make([
            'host' => 'imap.gmail.com',
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => env('IMAP_USERNAME'),
            'password' => env('IMAP_PASSWORD'),
            'protocol' => 'imap',
            'authentication' => null,
        ]);
    }

    protected function fetchUnreadEmails($client)
    {
        $folder = $client->getFolder('INBOX');
        return $folder->query()->unseen()->limit(10)->get();
    }

    protected function processMessages($messages)
    {
        $processedCount = 0;
        $errorCount = 0;

        foreach ($messages as $message) {
            try {
                $this->info("Processing: " . $message->getSubject());
                $this->processInboundEmail($message);
                $message->setFlag('Seen');
                $processedCount++;
                $this->info("Processed: " . $message->getSubject());
                
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("Failed: " . $message->getSubject());
                $this->error("   Error: " . $e->getMessage());
            }
        }

        $this->info("Completed! Processed: {$processedCount}, Errors: {$errorCount}");
    }

    protected function processInboundEmail($message)
    {
        $emailInfo = $this->extractEmailInfo($message);

        if ($this->emailExists($emailInfo['message_id'])) {
            return;
        }

        $threadInfo = $this->findOrCreateThreadInfo($emailInfo);
        $emailData = $this->prepareEmailData($emailInfo, $threadInfo);
        $this->saveEmail($emailData, $message);
    }

    protected function extractEmailInfo($message): array
    {
        return [
            'from' => $message->getFrom()[0]->mail ?? null,
            'to' => $message->getTo()[0]->mail ?? null,
            'subject' => $message->getSubject(),
            'html_body' => $message->getHTMLBody(),
            'text_body' => $message->getTextBody(),
            'message_id' => $message->getMessageId(),
            'in_reply_to' => $message->getInReplyTo(),
        ];
    }

    protected function emailExists(string $messageId): bool
    {
        return DB::table('emails')->where('message_id', $messageId)->exists();
    }

    protected function findOrCreateThreadInfo(array $emailInfo): array
    {
        $threadId = null;
        $replyToEmailId = null;
        $userId = null;

        if ($emailInfo['in_reply_to']) {
            $result = $this->findThreadByInReplyTo($emailInfo['in_reply_to']);
            if ($result) {
                return $result;
            }
        }

        if (!$threadId) {
            $result = $this->findThreadBySubject($emailInfo['subject']);
            if ($result) {
                $threadId = $result['thread_id'];
                $userId = $result['user_id'];
            }
        }

        if (!$userId) {
            $userId = $this->findUserByEmail($emailInfo['to']);
        }

        return [
            'thread_id' => $threadId,
            'reply_to_email_id' => $replyToEmailId,
            'user_id' => $userId,
        ];
    }

    protected function findThreadByInReplyTo(string $inReplyTo): ?array
    {
        $cleanInReplyTo = trim($inReplyTo, '<>');
        
        $originalEmail = DB::table('emails')
            ->where(function($q) use ($inReplyTo, $cleanInReplyTo) {
                $q->where('message_id', $inReplyTo)
                  ->orWhere('message_id', $cleanInReplyTo);
            })
            ->first();
        
        if ($originalEmail) {
            return [
                'thread_id' => $originalEmail->thread_id,
                'reply_to_email_id' => $originalEmail->id,
                'user_id' => $originalEmail->user_id,
            ];
        }

        return null;
    }

    protected function findThreadBySubject(string $subject): ?array
    {
        $cleanSubject = preg_replace('/^(Re:|RE:|Fwd:|FWD:)\s*/i', '', $subject);
        
        $thread = DB::table('email_threads')
            ->where('subject', 'LIKE', "%{$cleanSubject}%")
            ->orderBy('last_email_at', 'desc')
            ->first();
        
        if ($thread) {
            return [
                'thread_id' => $thread->id,
                'user_id' => $thread->user_id,
            ];
        }

        return null;
    }

    protected function findUserByEmail(?string $email): int
    {
        if (!$email) {
            return 1;
        }

        $user = DB::table('users')->where('email', $email)->first();
        return $user ? $user->id : 1;
    }

    protected function prepareEmailData(array $emailInfo, array $threadInfo): array
    {
        return [
            'from' => json_encode([['email' => $emailInfo['from']]]),
            'to' => json_encode([['email' => $emailInfo['to']]]),
            'subject' => $emailInfo['subject'],
            'reply' => $emailInfo['html_body'] ?: $emailInfo['text_body'],
            'rendered_content' => $emailInfo['html_body'] ?: $emailInfo['text_body'],
            'message_id' => $emailInfo['message_id'],
            'in_reply_to' => $emailInfo['in_reply_to'],
            'unique_id' => uniqid() . '@' . time() . '.inbound',
            'thread_id' => $threadInfo['thread_id'],
            'reply_to_email_id' => $threadInfo['reply_to_email_id'],
            'direction' => 'inbound',
            'status' => 'received',
            'folders' => json_encode(['inbox']),
            'is_read' => false,
            'user_id' => $threadInfo['user_id'],
            'user_type' => 'admin',
            'source' => 'gmail_imap',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    protected function saveEmail(array $emailData, $message)
    {
        DB::beginTransaction();

        try {
            $emailId = DB::table('emails')->insertGetId($emailData);

            if ($emailData['thread_id']) {
                $this->updateExistingThread($emailData['thread_id'], $emailId);
            } else {
                $this->createNewThread($emailData, $emailId);
            }

            $this->handleAttachments($message, $emailId);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function updateExistingThread(int $threadId, int $emailId)
    {
        DB::table('email_threads')->where('id', $threadId)->update([
            'last_email_at' => now(),
            'is_read' => false,
            'updated_at' => now(),
        ]);
        
        DB::table('email_threads')->where('id', $threadId)->increment('email_count');
        DB::table('emails')->where('id', $emailId)->update(['thread_id' => $threadId]);
    }

    protected function createNewThread(array $emailData, int $emailId)
    {
        $newThreadId = DB::table('email_threads')->insertGetId([
            'subject' => $emailData['subject'],
            'folder' => 'inbox',
            'user_id' => $emailData['user_id'],
            'last_email_at' => now(),
            'email_count' => 1,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('emails')->where('id', $emailId)->update(['thread_id' => $newThreadId]);
    }

    protected function handleAttachments($message, int $emailId)
    {
        if (!$message->hasAttachments()) {
            return;
        }

        foreach ($message->getAttachments() as $attachment) {
            $this->saveAttachment($attachment, $emailId);
        }
    }

    protected function saveAttachment($attachment, int $emailId)
    {
        $filename = $attachment->getName();
        $path = 'email-attachments/' . uniqid() . '_' . $filename;
        $fullPath = storage_path('app/' . $path);
        
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($fullPath, $attachment->getContent());
        
        DB::table('email_attachments')->insert([
            'email_id' => $emailId,
            'name' => $filename,
            'path' => $path,
            'size' => strlen($attachment->getContent()),
            'content_type' => $attachment->getContentType(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}