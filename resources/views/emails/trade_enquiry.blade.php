<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Trade Enquiry</title>
</head>
<body>
    <h2>New Trade Enquiry From Website</h2>

    <p><strong>Business Name:</strong> {{ $data['business_name'] }}</p>
    <p><strong>Contact Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Phone Number:</strong> {{ $data['phone'] ?? '-' }}</p>

    <p><strong>Message / Requirements:</strong></p>
    <p>{{ nl2br(e($data['message'])) }}</p>
</body>
</html>
