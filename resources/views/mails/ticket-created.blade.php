<!DOCTYPE html>
<html>
<head>
    <title>Ticket Created</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6;">
    <h2>Hi {{ $ticket->customer_name }},</h2>
    <p>Thank you for contacting the support system. Your ticket has been successfully created.</p>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Reference:</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $ticket->ref }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Description:</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $ticket->description }}</td>
        </tr>
    </table>

    <p><a href="{{ route('tickets.show', $ticket->ref) }}">Click here to view your ticket status.</a></p>
    
    <hr>
    <p style="font-size: 0.8em;">This is an automated message from the Help Desk System.</p>
</body>
</html>