<!DOCTYPE html>
<html>
<head>
    <title>Job Offer</title>
</head>
<body>
    <p>Dear {{ $candidate->name }},</p>
    <p>We are pleased to offer you the position of {{ $jobTitle }} at our company. Please see the attached PDF for more details.</p>
    <p>Your Job Offer ID is: {{ $jobOffer->id }}</p>
    <p>Best regards,</p>
    <p>Your Company</p>
</body>
</html>
