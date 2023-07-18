<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
</head>
 
<body>
    <table>
        <tr>
            <td>Dear, Admin</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Name : {{ $data['name'] }}</td>
        </tr>
 
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Email : {{ $data['email'] }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                {{ $data['message'] }}
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Thanks and regards,</td>
        </tr>
        <tr>
            <td>{{ $data['name'] }}</td>
        </tr>
    </table>
</body>
 
</html>
