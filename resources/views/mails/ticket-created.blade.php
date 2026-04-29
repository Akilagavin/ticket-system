<!DOCTYPE html>
<html>
<head>
    <title>Ticket Created</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6;">
    <h2>Hello {{ $ticket->customer_name }},</h2>
    <p>Your support ticket has been successfully created.</p>
    <p><strong>Reference Number:</strong> {{ $ticket->ref }}</p>
    <p>We will review your request and get back to you shortly.</p>
    <hr>
    <p>This is an automated message from the Help Desk System.</p>
</body>
</html>