<table>
    <tbody>
        <tr>
            <td>UUID</td>
            <td>{{ $payment['uuid'] }}</td>
        </tr>
        <tr>
            <td>Payment Date</td>
            <td>{{ $payment['payment_date'] }}</td>
        </tr>
        <tr>
            <td>Expires At</td>
            <td>{{ $payment['expires_at'] }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>{{ $payment['status'] }}</td>
        </tr>
        <tr>
            <td>CLP USD</td>
            <td>{{ $payment['clp_usd'] ?? '' }}</td>
        </tr>
        <tr>
            <td>Client Email</td>
            <td>{{ $client['email'] }}</td>
        </tr>
    </tbody>
</table>
