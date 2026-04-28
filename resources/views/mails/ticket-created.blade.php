<p>
    Hi {{ $ticket->customer_name }},
</p>

<p>
    Thank you for contacting the support system.
    Your ticket is successfully created and details are shown below:
</p>

<br>

<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <td><strong>Ticket Refererence:</strong></td>
            <td>{{ $ticket->ref }}</td>
        </tr>
        <tr>
            <td><strong>Customer Name:</strong></td>
            <td>{{ $ticket->customer_name }}</td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td>{{ $ticket->email }}</td>
        </tr>
        <tr>
            <td><strong>Phone:</strong></td>
            <td>{{ $ticket->phone }}</td>
        </tr>
        <tr>
            <td><strong>Description:</strong></td>
            <td>{{ $ticket->description }}</td>
        </tr>
    </tbody>
</table>

<br><br>

<a href="{{ route('tickets.show', $ticket->id) }}">Click here to view your ticket</a>

<br>

<p>Thank you.</p>