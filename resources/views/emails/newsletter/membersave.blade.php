<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html lang="pl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
<body bgcolor="#e3e2e8" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="-webkit-font-smoothing: antialiased;width:100% !important;background:#e3e2e8;-webkit-text-size-adjust:none;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#e3e2e8">
    <tr>
        <td bgcolor="#e3e2e8" width="100%">

<table width="600" cellpadding="0" cellspacing="0" border="0" align="center">
    <tr>
        <td width="600">

        <table width="600" cellpadding="25" cellspacing="0" border="0">
    <tr>
        <td bgcolor="#018187" width="600">
            <multiline label="Main feature intro">
                <p class="white-font" style="color:#e3e2e8;font-size:18px;font-weight: bold;line-height:26px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:14px;font-weight: bold;">
                    Dziękujemy, że jesteś z nami.<br>
                    Aby otrzymywać newsletter Domu Spotkań z Historią wystarczy kliknąć w
                    ten link: <a href="{{ url('api/newsletter/confirm/'.$member->verification_token) }}">{{ url('api/newsletter/confirm/'.$member->verification_token) }}</a><br>
                    Pamiętaj, że możesz zaktualizować swoje dane lub zrezygnować z
                    subskrypcji w dowolnym momencie.<br>
                    Jeśli nie zapisywałeś się do naszego newslettera, zignoruj tę wiadomość.
                </p>
            </multiline>

        </td>
    </tr>
</table>
<repeater>
<!--
<layout label="Link potwierdzenia">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="100%" bgcolor="#e3e2e8">
                <table width="100%" cellpadding="20" cellspacing="0" border="0">
                    <tr>
                        <td bgcolor="#e3e2e8" class="contentblock">
                            Aby aktywować konto kliknij poniższy link aktywacyjny.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</layout>
-->
<!--
<layout label="Szczegoly zamowienia">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="100%" bgcolor="#e3e2e8">
                <table width="100%" cellpadding="20" cellspacing="0" border="0">
                    <tr>
                        <td bgcolor="#e3e2e8" class="contentblock">
                            <multiline label="Description">
                                
                            </multiline>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</layout>
-->
</repeater>
        </td>
    </tr>
</table>
        </td>
    </tr>
</table>
</body>
</html>