<?php
require('../assets/fpdf/fpdf.php'); // Atur path sesuai dengan lokasi FPDF di proyek Anda

class PDF extends FPDF {
    // Header
    function Header() {
        // Logo
        $this->Image('logo telkomm.png', 10, 6, 30);
        $this->SetFont('Arial', 'B', 12);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30, 10, 'INVOICE', 0, 1, 'C');
        $this->Ln(10);
    }

    // Footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();

// Judul
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'INVOICE', 0, 1, 'C');
$pdf->Ln(10);

// Informasi Perusahaan
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'PUBLISH IN THE NAME OF', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'PT. jaya sentosa mantap wow', 0, 1);
$pdf->Ln(10);

// Informasi Tagihan
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'BILL TO', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'Zaidan Halim', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'Date : 24 june 2024', 0, 1);
$pdf->Cell(190, 10, 'Ship To : Zaidan (6285719563213)', 0, 1);
$pdf->MultiCell(190, 10, 'Jl. Teknik Kimia, Keputih, Kec. Sukolilo, Surabaya, Jawa Timur 60111 Indonesia', 0, 1);
$pdf->Ln(10);

// Tabel Produk
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'PRODUCT INFO', 1, 0, 'C');
$pdf->Cell(30, 10, 'AMOUNT', 1, 0, 'C');
$pdf->Cell(50, 10, 'UNIT PRICE', 1, 0, 'C');
$pdf->Cell(50, 10, 'PRICE TOTAL', 1, 1, 'C');

$products = [
    ['name' => 'Gold', 'amount' => '2 Kgs', 'unit_price' => 'IDR 130.000.000', 'price_total' => 'IDR 260.000.000'],
    ['name' => 'Diamond', 'amount' => '1 Kgs', 'unit_price' => 'IDR 900.000.000', 'price_total' => 'IDR 900.000.000'],
    ['name' => 'Emerald', 'amount' => '1 Kgs', 'unit_price' => 'IDR 80.000.000', 'price_total' => 'IDR 160.000.000'],
    ['name' => 'Crystal', 'amount' => '1 kgs', 'unit_price' => 'IDR 999.000.000', 'price_total' => 'IDR 999.000.000'],
];

// Isi Tabel Produk
$pdf->SetFont('Arial', '', 12);
foreach ($products as $product) {
    $pdf->Cell(60, 10, $product['name'], 1, 0);
    $pdf->Cell(30, 10, $product['amount'], 1, 0, 'C');
    $pdf->Cell(50, 10, $product['unit_price'], 1, 0, 'R');
    $pdf->Cell(50, 10, $product['price_total'], 1, 1, 'R');
}
$pdf->Ln(10);

// Total
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(110, 10, 'Service', 1, 0);
$pdf->Cell(80, 10, 'IDR 1.000', 1, 1, 'R');
$pdf->Cell(110, 10, 'Shipping Cost', 1, 0);
$pdf->Cell(80, 10, 'IDR 700.000', 1, 1, 'R');
$pdf->Cell(110, 10, 'Discount Shipping C', 1, 0);
$pdf->Cell(80, 10, '-IDR 700.000', 1, 1, 'R');
$pdf->Cell(110, 10, 'Discount Total', 1, 0);
$pdf->Cell(80, 10, '-IDR 700.000', 1, 1, 'R');
$pdf->Cell(110, 10, 'Shipping Insurance', 1, 0);
$pdf->Cell(80, 10, 'IDR 700.000', 1, 1, 'R');
$pdf->Cell(110, 10, 'Spending Total', 1, 0);
$pdf->Cell(80, 10, 'IDR 17.760', 1, 1, 'R');
$pdf->Cell(110, 10, 'Price Total', 1, 0);
$pdf->Cell(80, 10, 'IDR 2.319.000.000', 1, 1, 'R');

// Output PDF
$pdf->Output('invoice.pdf', 'D'); // Output sebagai file 'invoice.pdf'

?>
