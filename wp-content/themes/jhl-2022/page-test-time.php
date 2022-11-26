<html>
<body>
    <table>
    <tr>
        <td style="width: 400px;">time()</td>
        <td><?php echo time();?> </td>
    </tr>
    <tr>
        <td>date('Y-m-d H:i:s', time())</td>
        <td><?php echo date('Y-m-d H:i:s', time());?> </td>
    </tr>

    <tr>
        <td>current_time('timestamp')</td>
        <td><?php print_r(current_time('timestamp')); ?></td>
    </tr>
    <tr>
        <td>date('Y-m-d H:i:s', current_time('timestamp'))</td>
        <td><?php echo date('Y-m-d H:i:s', current_time('timestamp')); ?> </td>
    </tr>

    <tr>
        <td>current_datetime()</td>
        <td><?php print_r(current_datetime()); ?></td>
    </tr>

    <tr>
        <td>current_datetime()->getTimestamp()</td>
        <td><?php print_r(current_datetime()->getTimestamp()); ?></td>
    </tr>
    <tr>
        <td>current_datetime()->getOffset()</td>
        <td><?php print_r(current_datetime()->getOffset()); ?></td>
    </tr>

    <tr>
        <td>current_datetime()->format( 'Y-m-d H:i:s' )</td>
        <td><?php print_r(current_datetime()->format( 'Y-m-d H:i:s' )); ?></td>
    </tr>

    <tr>
        <td>current_datetime()->getTimestamp() + current_datetime()->getOffset()</td>
        <td><?php print_r(current_datetime()->getTimestamp() + current_datetime()->getOffset()); ?></td>
    </tr>

    </table>
</body>

