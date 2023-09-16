<?php

$conn = mysqli_connect("localhost", "root", "", "connecting_ifes"); //Conexão Sem PDO

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=connecting_ifes', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Conexãoo Com PDO

?>

<html>

<head>
    <meta charset="utf-8">
    <title>ConnectingIFES</title>
    <!-- Animação fade effect -->
    <style type="text/css">
        html {
            -webkit-animation: fadein 2s;
            /* Safari, Chrome and Opera > 12.1 */
            -moz-animation: fadein 2s;
            /* Firefox < 16 */
            -ms-animation: fadein 2s;
            /* Internet Explorer */
            -o-animation: fadein 2s;
            /* Opera < 12.1 */
            animation: fadein 2s;
        }

        @keyframes fadein {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Firefox < 16 */
        @-moz-keyframes fadein {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Safari, Chrome and Opera > 12.1 */
        @-webkit-keyframes fadein {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Internet Explorer */
        @-ms-keyframes fadein {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Opera < 12.1 */
        @-o-keyframes fadein {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

</html>