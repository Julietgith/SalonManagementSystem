<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../includes/db_connect.php';

    $mpdf = new Mpdf\Mpdf();
    header('Content-Type: application/pdf');

    $stmt = $pdo->query("SELECT * FROM services");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = 1;

    $html = '
    <html>
        <head>
            <style>
                body {
                    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                    font-size: 12px;
                    padding: 20px;
                    color: #333;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                .signature-section {
                    margin-top: 50px;
                }
                .signature p {
                    margin: 0;
                }
            </style>
        </head>
        <body>
            <h4>Product List</h4>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        
                        <th>Price</th>
                    
                    </tr>
                </thead>
                <tbody>';
                
                foreach ($services as $services) {
                    $html .= '
                    <tr>
                        <td>' . $count++ . '</td>
                        
                        <td>' . htmlspecialchars($services['name']) . '</td>
                        <td>' . htmlspecialchars($services['price']) . '</td>
                    </tr>';
                }

                $html .= '
                </tbody>
            </table>

            <div class="signature-section">
                <p>____________________________</p>
                <p><strong>General Manager</strong></p>
            </div>
        </body>
    </html>';

    $mpdf->SetHTMLFooter('<div style="text-align: left;">Page {PAGENO}/{nbpg}</div>');
    $mpdf->WriteHTML($html);
    $mpdf->Output('', 'I');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <form method="POST" action="simple_print.php">
        <button type="submit" class="btn btn-primary">Print Services</button>
    </form>
</body>
</html>
