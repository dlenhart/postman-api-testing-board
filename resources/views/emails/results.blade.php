<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <style>

        .terminal {
            padding: 20px;
            color: black;
            box-sizing: border-box;
            border-bottom: 1px solid #edeff2;
            border-left: 1px solid #edeff2;
            border-right: 1px solid #edeff2;
            background-color: #fefefe !important;
        }

        .terminalLink:link, terminalLink:visited {
            color: black !important;
            text-decoration: underline !important;
        }

        .boldme {
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .badge-pill {
            padding-right: 0.6em;
            padding-left: 0.6em;
            border-radius: 10rem;
        }

        .badge-success {
            color: #fff;
            background-color: #28a745;
        }

        .badge-failure {
            color: #fff;
            background-color: #dc3545;
        }

        .badge-dark {
            color: #fff;
            background-color: black;
        }

    </style>
</head>
<body style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #fefefe; color: #74787e; height: 100%; line-height: 1.4; margin: 0; width: 100% !important; -webkit-text-size-adjust: none;">
    
    <center>
    <h1><span class='badge badge-dark'><em>{{ Str::upper($result->application->name) }}</em></span></h1>
    <p> <em> Tests were executed on successfull deployment from the "<strong>{{ $result->branch }}</strong>" branch.</em></p>
    <p>
        <em> The results can also be found on the <a href="{{ URL::to("/app/{$result->application->name}") }}">dashboard</a>. </em>
    </p>
    </center>

    <table width="100%"
       cellpadding="0"
       cellspacing="0"
       style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #fefefe; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
    <tr>
        <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
            <table class="content"
                   width="75%"
                   cellpadding="0"
                   cellspacing="0"
                   style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                   <thead><tr><th>Assertions</th><th>Iterations</th><th>Requests</th><th>Duration</th></tr></thead>
                   <tr>
                    <td class="body"
                        style="text-align:center; font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid #edeff2; border-top: 1px solid #edeff2; margin: 0; padding: 0; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                        
                        <span
                            class='badge badge-pill {{$result->assertions_failed > 0 ? "badge-failure" : "badge-success"}}'>
                         {{ $result->assertions_failed }} / {{ $result->assertions_total }}</span>
                    </td>

                    <td class="body"
                        style="text-align:center; font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid #edeff2; border-top: 1px solid #edeff2; margin: 0; padding: 0; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                    
                        <span
                            class='badge badge-pill {{$result->iterations_failed > 0 ? "badge-failure" : "badge-success"}}'>
                            {{ $result->iterations_failed }} / {{ $result->iterations_total }}</span>
                    </td>
                    <td class="body"
                        style="text-align:center; font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;border-bottom: 1px solid #edeff2; border-top: 1px solid #edeff2; margin: 0; padding: 0; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                        <span
                        class='badge badge-pill {{$result->requests_failed > 0 ? "badge-failure" : "badge-success"}}'>
                            {{ $result->requests_failed }} / {{ $result->requests_total }}</span>
                    </td>

                    <td class="body"
                        style="text-align:center; font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid #edeff2; border-top: 1px solid #edeff2; margin: 0; padding: 0; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                    
                        <span class='badge badge-pill badge-dark'><strong>{{ $result->duration }} ms</strong></span>
                    </td>
                </tr>
            </table>
                <table class="content"
                   width="75%"
                   cellpadding="3"
                   cellspacing="1"
                   style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                   <thead><tr><strong><br />Results</strong></tr></thead>
                   <tr>
                        <td class="body"
                            style="text-align:left; font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid #edeff2; border-top: 1px solid #edeff2; margin: 0; padding: 0; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                            
                            <div class="terminal">
                                {!! $result->parsed_results !!}
                            </div>
                        </td>

                    </tr>
                </table>
            </table>
        </td>
    </tr>
</table>
</body>
</html>