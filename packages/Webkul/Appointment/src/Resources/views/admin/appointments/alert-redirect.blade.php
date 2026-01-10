<!DOCTYPE html>
<html>
<head>
    <title>Thông báo</title>
</head>
<body>
    <script>
        alert(@json($message)); // Hiện thông báo
        window.location.href = "{{ route('admin.appointments.index') }}"; // Redirect về index
    </script>
</body>
</html>
