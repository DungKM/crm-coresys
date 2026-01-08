<?php

namespace Webkul\EmailExtended\Repositories;

use Webkul\Email\Repositories\EmailRepository as BaseEmailRepository;
use Illuminate\Support\Facades\DB;

class EmailRepository extends BaseEmailRepository
{
    /**
     * Override sanitizeEmails để xử lý field 'to'
     * Đảm bảo 'to' là array hợp lệ, loại bỏ giá trị null/empty
     * 
     * @param array $data
     * @return array
     */
    public function sanitizeEmails(array $data)
    {
        if (isset($data['to'])) {
            $data['to'] = array_values(array_filter($data['to']));
        }
        
        return parent::sanitizeEmails($data);
    }
    
    /**
     * Override create để đảm bảo các field quan trọng không bị mất
     * Parent repository có thể làm mất một số field, phương thức này sẽ restore lại
     * 
     * Fields được bảo vệ: to, user_id, reply_to_email_id, thread_id, status, folders, scheduled_at
     * 
     * @param array $data
     * @return \Webkul\EmailExtended\Models\Email
     */
    public function create(array $data)
    {
        // Backup các field quan trọng TRƯỚC KHI gọi parent::create()
        $toValue = $data['to'] ?? null;
        $userId = $data['user_id'] ?? null;
        $replyToEmailId = $data['reply_to_email_id'] ?? null;
        $threadId = $data['thread_id'] ?? null;
        $status = $data['status'] ?? null;
        $folders = $data['folders'] ?? null;
        $scheduledAt = $data['scheduled_at'] ?? null;
        
        // Gọi parent create (có thể làm mất một số field)
        $email = parent::create($data);
        
        // Kiểm tra và khôi phục các field bị mất
        $needUpdate = false;
        $updateData = [];
        
        // Restore TO field nếu bị mất
        if ($toValue !== null && empty($email->to)) {
            $updateData['to'] = json_encode($toValue);
            $needUpdate = true;
        }
        
        // Restore USER_ID field nếu bị mất
        if ($userId !== null && empty($email->user_id)) {
            $updateData['user_id'] = $userId;
            $needUpdate = true;
        }
        
        // Restore REPLY_TO_EMAIL_ID field nếu bị mất
        if ($replyToEmailId !== null && empty($email->reply_to_email_id)) {
            $updateData['reply_to_email_id'] = $replyToEmailId;
            $needUpdate = true;
        }
        
        // Restore THREAD_ID field nếu bị mất
        if ($threadId !== null && empty($email->thread_id)) {
            $updateData['thread_id'] = $threadId;
            $needUpdate = true;
        }
        
        // Restore STATUS field nếu bị thay đổi
        if ($status !== null && $email->status !== $status) {
            $updateData['status'] = $status;
            $needUpdate = true;
        }
        
        // Restore FOLDERS field nếu bị thay đổi
        if ($folders !== null && json_encode($email->folders) !== json_encode($folders)) {
            $updateData['folders'] = json_encode($folders);
            $needUpdate = true;
        }
        
        // Restore SCHEDULED_AT field nếu bị mất
        if ($scheduledAt !== null && $email->scheduled_at != $scheduledAt) {
            $updateData['scheduled_at'] = $scheduledAt;
            $needUpdate = true;
        }
        
        // Thực hiện update nếu có field bị mất
        if ($needUpdate) {
            DB::table('emails')
                ->where('id', $email->id)
                ->update($updateData);
            
            // Reload email từ database sau khi update
            $email = $this->find($email->id);
        }
        
        return $email;
    }

    /**
     * Override update để đảm bảo các field quan trọng không bị thay đổi
     * 
     * Fields được bảo vệ: status, folders, scheduled_at
     * 
     * @param array $data
     * @param int $id
     * @param string $attribute
     * @return \Webkul\EmailExtended\Models\Email
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        // Backup các field quan trọng TRƯỚC KHI gọi parent::update()
        $status = $data['status'] ?? null;
        $folders = $data['folders'] ?? null;
        $scheduledAt = $data['scheduled_at'] ?? null;

        // Gọi parent update (có thể làm thay đổi hoặc mất field)
        $email = parent::update($data, $id, $attribute);

        // Kiểm tra và khôi phục các field bị thay đổi
        $needUpdate = false;
        $updateData = [];
        
        // Restore STATUS nếu bị thay đổi
        if ($status !== null && $email->status !== $status) {
            $updateData['status'] = $status;
            $needUpdate = true;
        }
        
        // Restore FOLDERS nếu bị thay đổi
        if ($folders !== null && json_encode($email->folders) !== json_encode($folders)) {
            $updateData['folders'] = json_encode($folders);
            $needUpdate = true;
        }

        // Restore SCHEDULED_AT nếu bị thay đổi
        if ($scheduledAt !== null && $email->scheduled_at != $scheduledAt) {
            $updateData['scheduled_at'] = $scheduledAt;
            $needUpdate = true;
        }

        // Thực hiện force update nếu cần
        if ($needUpdate) {
            DB::table('emails')
                ->where('id', $email->id)
                ->update($updateData);
            
            // Reload email từ database sau khi force update
            $email = $this->find($email->id);
        }
        
        return $email;
    }
}