<!DOCTYPE html>
<html>
<body style="margin:0; padding:0; background:#f3f3f3; font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#e9e9e9; padding:20px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#fff; border:1px solid #ddd; border-radius:10px; overflow:hidden;">
                <tr>
                    <td align="center" style="padding:20px 0;">
                        <img src="{{ asset('img/logo.png') }}" width="200" style="display:block;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="height:5px; width:100%; background:#da251c;"></div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px; font-size:14px; color:#333;">
                        @if($heading)
                            <h3 style="margin-top:0; color:#222;">{{ $heading }}</h3>
                        @endif

                        {!! $content !!}
                        <br><br>
                        <p>
                            <strong>Regards</strong><br>
                            {{ config('app.name') }}
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center" style="padding:15px; border-top:1px solid #ddd; font-size:14px; line-height:1.7; color:#555;">
                     
                        <div style="width:30%; height:1px; background:#ddd; margin:10px auto;"></div>
                        <p>
                            Email: {{ config('app.email', 'support@example.com') }}<br>
                            Phone: {{ config('app.phone', '+91-1234567890') }}
                        </p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>

</body>
</html>
