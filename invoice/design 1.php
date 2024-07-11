<?php
require('../assets/fpdf/fpdf.php');

class PDF extends FPDF
{
    // Footer method to add text at the bottom
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('Arial', 'I', 10);
        // Text color in gray
        $this->SetTextColor(128, 128, 128);
        // Centered text
        $this->Cell(0, 10, 'beelife.binus.org', 0, 0, 'C');
    }
}

// Create instance of the PDF class
$pdf = new PDF();
$pdf->AddPage();

// Set font for the title
$pdf->SetFont('Arial', 'B', 14);

// Add title
$pdf->Cell(0, 10, 'Discover Your Potential with Beelife', 0, 1, );
$pdf->Ln(5);

// Set font for the subtitle
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(255, 140, 0); // Orange color for "HALO CHANDRA!"
$pdf->Cell(0, 10, 'HALO CHANDRA!', 0, 1, 'L');
$pdf->Ln(5);

// Reset font and color for body text
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

// Define the text content
$text = "Selamat! Kamu telah berhasil menyelesaikan semua assessment di Beelife dengan cemerlang. "
    . "Sekarang, kami memiliki banyak informasi berharga tentang dirimu yang bisa kita eksplorasi bersama. "
    . "Kami percaya setiap individu memiliki keunikan dan kompetensi yang berbeda, dan Beelife hadir untuk "
    . "membantumu menemukan keunikan dan memaksimalkan potensi itu.\n\n"

    . "Di Beelife, kamu telah mengeksplorasi Minat, Adaptabilitas Karir, Soft Skills, dan Love Language kamu. "
    . "Semua informasi ini telah di rangkum dalam laporan komprehensif yang benar-benar menggambarkan dirimu. "
    . "Beelife sendiri dirancang untuk membimbingmu dalam menetapkan visi dan merencanakan masa depan gemilang "
    . "berdasarkan data diri tentang kekuatan dan potensi dirimu. Laporan ini adalah panduan lengkap tentang Chandra, "
    . "bagaikan \"Ensiklopedia tentang Chandra\"!\n\n"

    . "Dengan memahami keunikan, potensi, dan berbagai aspek dirimu, kamu akan menemukan cara terbaik untuk mengembangkan "
    . "diri dan meraih kesuksesan dalam bidang akademik, karier, dan kehidupan yang seimbang. Beelife di Binus University "
    . "membantu mengerti keunikan serta kelemahan, sehingga kamu dapat menjadi individu yang visioner, kuat, dan berdampak "
    . "dalam perjalanan hidupmu. Memaksimalkan keunikanmu tidak hanya membantumu dalam mencapai tujuan-tujuan yang tepat, "
    . "tetapi juga memberikan panduan dalam merancang langkah-langkah konkret menuju masa depan yang gemilang dan penuh "
    . "kesuksesan. Dengan bimbingan ini, setiap langkah yang kamu ambil akan terarah dengan baik dan penuh persiapan, "
    . "menginspirasimu untuk menghadapi tantangan dengan lebih baik dan mencapai tujuan yang telah kamu tetapkan.";

// Add the text to the PDF
$pdf->MultiCell(0, 6, $text);

// Output the PDF
$pdf->Output('I', 'beelife_chandra.pdf');
?>
