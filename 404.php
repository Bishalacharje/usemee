<!-- 404.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>404 | Usemee - Your one-stop online store for all your shopping needs!</title>
    <!------------------------------- favicon ------------------ -->
    <link rel="shortcut icon" href="assets/images/logo/fav.png" type="image/x-icon" />
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh
        }

        img {
            width: 30%;
        }

        p {
            font-size: 18px;
            font-family: "Roboto Condensed", sans-serif;
            margin: -8px 0 36px;
            position: relative;
            z-index: 2;
            text-align: center;
        }

        a {
            text-decoration: none;
        }

        a button {
            font-family: "Roboto Condensed", sans-serif;
            background-color: #80B500;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            column-gap: 10px;
            width: 200px;
            height: 54px;
            color: #fff;
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        @media (max-width: 750px) {
            img {
                width: 50%;
            }

            p {
                font-size: 14px;
                margin: -2px 0 24px;
            }

            a button {
                width: 108px;
                height: 32px;
                font-size: 10px;
            }
        }
    </style>
</head>

<body>
    <img src="assets/images/404.png" alt="">
    <p>The page you are looking for doesn't exist.</p>
    <a href="index.php"><button>Home</button></a>
</body>

</html>