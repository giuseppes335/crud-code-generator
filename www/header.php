<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="data:,">
    <title>Crud Code Generator</title>
    <style>

        html {
            margin: 0px;
            padding: 0px;
        }

        body {
            font-family: "Calibri Light";

            margin: 0px;
            padding: 0px;
        }

        .sezione-centro {
            display: flex;
            flex-direction: column;
            margin-bottom: 24px;
        }

        .elenco li {
            margin: 4px 0;
            padding: 8px 0;
        }

        .testo-grande {
            margin: 4px 0;
            padding: 8px 0px;
        }

        .contenitore {
            max-width: 1024px;
            margin: 0 auto;
        }

        .contenitore-parte-superiore {
            display: flex;
        }

        .parte-sinistra {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            padding: 0 8px;
        }

        .banda {
            background-color: #2f2d2d;
            min-width: 150px;
        }

        @media screen and (max-width: 600px) {
            .banda {
                min-width: 0px;
                width: 0px;
            }
        }

        .footer {
            display: flex;
            max-width: 1024px;
            margin: 0 auto;
            border-top: 1px solid #2f2d2d;
            padding: 0 8px;
            background-color: #2f2d2d;
            color: #dcdcdc;
        }

        label, textarea {
            width: 100%;
            display: block;
        }

    </style>
</head>

<body>
<div class="contenitore">

    <div class="contenitore-parte-superiore">

        <div class="parte-sinistra">

            <h1><a href="/">Home</a></h1>

            <div class="sezione-centro">
