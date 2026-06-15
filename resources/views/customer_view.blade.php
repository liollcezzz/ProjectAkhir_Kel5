<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer View</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f5f5f5;
        }

        .container{
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1{
            color: #333;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td{
            border: 1px solid #ddd;
        }

        th, td{
            padding: 10px;
            text-align: left;
        }

        th{
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Data Customer</h1>

        <table>
            <tr>
                <th>No</th>
                <th>Nama Customer</th>
                <th>Email</th>
            </tr>

            <tr>
                <td>1</td>
                <td>customer</td>
                <td>customer@gmail.com</td>
            </tr>

            <tr>
                <td>2</td>
                <td>Customer 2</td>
                <td>customer2@gmail.com</td>
            </tr>
        </table>
    </div>

</body>
</html>