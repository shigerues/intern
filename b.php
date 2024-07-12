<?php
require('assets/fpdf/fpdf.php');

class PDF extends FPDF {
    // Header halaman
    function Header() {
        // Logo di pojok kanan atas
<<<<<<< Updated upstream
        $this->Image('assets/img/binus.png', 170, 10, 35); // Sesuaikan posisi dan ukuran jika perlu
=======
        $this->Image('../assets/img/binus.png', 170, 10, 35); // Sesuaikan posisi dan ukuran jika perlu
>>>>>>> Stashed changes
        // Set font untuk title
        $this->SetFont('Arial', 'B', 27);
        // Buat warna
        $this->SetTextColor(252, 182, 193);
        // Pindah ke kanan
        $this->Cell(80);
        // Judul
        $this->Cell(30, 120, 'LAPORAN POTENSI DIRI', 0, 1, 'C');
        // Jeda baris
        $this->Ln(20);

<<<<<<< Updated upstream
        // Tambahkan deretan lingkaran di pojok kiri atas (25 lingkaran)
        $this->AddCircles(10, 10, 2, 3, 5, 5, [252, 111, 173]); // Pojok kiri atas, 5x5 grid, 25 lingkaran
=======
        // Tambahkan deretan lingkaran di kanan atas
        $this->AddCircles(170, 10, 10, 7, 5, [252, 111, 173]);
>>>>>>> Stashed changes
    }

    // Footer halaman
    function Footer() {
        // Posisi 1.5 cm dari bawah
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Nomor halaman
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'C');

        // Tambahkan BEELIFE.BINUS.ORG di tengah bawah halaman pertama
        if ($this->PageNo() == 1) {
            $this->SetY(-30); // Sesuaikan posisi Y
            $this->Cell(0, 10, 'BEELIFE.BINUS.ORG', 0, 0, 'C');
        }

     // Tambahkan deretan lingkaran di pojok kanan atas
     $this->AddCircles($this->GetPageWidth() - 20 - 2 * 5 * 3, 250, 2, 3, 5, 5, [252, 111, 173]); // Pojok kanan atas, 5x5 grid, 25 lingkaran
    }

    // Fungsi untuk menambahkan deretan lingkaran
    function AddCircles($x, $y, $radius, $spacing, $rows, $cols, $color) {
        // Set warna isi
        $this->SetFillColor($color[0], $color[1], $color[2]);

        for ($row = 0; $row < $rows; $row++) {
            for ($col = 0; $col < $cols; $col++) {
                $cx = $x + $col * ($radius * 2 + $spacing);
                $cy = $y + $row * ($radius * 2 + $spacing);
                $this->Circle($cx, $cy, $radius, 'F');
            }
        }
    }

    // Fungsi untuk menggambar lingkaran
    function Circle($x, $y, $r, $style = 'D') {
        $this->Ellipse($x, $y, $r, $r, $style);
    }

    // Fungsi untuk menggambar elips (lingkaran)
    function Ellipse($x, $y, $rx, $ry, $style = 'D') {
        if ($style == 'F') {
            $style = 'f';
        } elseif ($style == 'DF' || $style == 'FD') {
            $style = 'B';
        }

        $op = $style == 'f' ? 'f' : ($style == 'B' ? 'B' : 'S');

        $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;
        $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;

        $this->_out(sprintf('%.2F %.2F m', ($x + $rx) * $this->k, ($this->h - $y) * $this->k));
        $this->_Arc($x + $rx, $y - $ly, $x + $lx, $y - $ry, $x, $y - $ry);
        $this->_Arc($x - $lx, $y - $ry, $x - $rx, $y - $ly, $x - $rx, $y);
        $this->_Arc($x - $rx, $y + $ly, $x - $lx, $y + $ry, $x, $y + $ry);
        $this->_Arc($x + $lx, $y + $ry, $x + $rx, $y + $ly, $x + $rx, $y);
        $this->_out($op);
    }

    // Fungsi untuk menambahkan arc
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }

    // Fungsi untuk menambahkan deretan lingkaran
    function AddCircles($x, $y, $radius, $spacing, $rows, $color) {
        // Set warna isi
        $this->SetFillColor($color[0], $color[1], $color[2]);

        for ($row = 0; $row < $rows; $row++) {
            for ($col = 0; $col < $rows; $col++) {
                $this->Ellipse($x + $col * ($radius * 2 + $spacing), $y + $row * ($radius * 2 + $spacing), $radius, $radius, 'F');
            }
        }
    }

    // Fungsi untuk menggambar lingkaran
    function Ellipse($x, $y, $rx, $ry, $style = 'D') {
        if ($style == 'F') {
            $style = 'f';
        } elseif ($style == 'DF' || $style == 'FD') {
            $style = 'B';
        }

        $op = $style == 'f' ? 'f' : ($style == 'B' ? 'B' : 'S');

        $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;
        $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;

        $this->_out(sprintf('%.2F %.2F m', $x + $rx, $y));
        $this->_Arc($x + $rx, $y - $ly, $x + $lx, $y - $ry, $x, $y - $ry);
        $this->_Arc($x - $lx, $y - $ry, $x - $rx, $y - $ly, $x - $rx, $y);
        $this->_Arc($x - $rx, $y + $ly, $x - $lx, $y + $ry, $x, $y + $ry);
        $this->_Arc($x + $lx, $y + $ry, $x + $rx, $y + $ly, $x + $rx, $y);
        $this->_out($op);
    }

    // Fungsi untuk menambahkan arc
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }

    // Fungsi untuk membuat latar belakang gradasi
    function Gradient($x, $y, $w, $h, $col1, $col2, $arah = 'vertical') {
        if ($arah == 'vertical') {
            $this->Rect($x, $y, $w, $h, 'F');
            for ($i = 0; $i < $h; $i++) {
                $r = $col1[0] + ($col2[0] - $col1[0]) * $i / $h;
                $g = $col1[1] + ($col2[1] - $col1[1]) * $i / $h;
                $b = $col1[2] + ($col2[2] - $col1[2]) * $i / $h;
                $this->SetFillColor($r, $g, $b);
                $this->Rect($x, $y + $i, $w, 1, 'F');
            }
        } else {
            $this->Rect($x, $y, $w, $h, 'F');
            for ($i = 0; $i < $w; $i++) {
                $r = $col1[0] + ($col2[0] - $col1[0]) * $i / $w;
                $g = $col1[1] + ($col2[1] - $col1[1]) * $i / $w;
                $b = $col1[2] + ($col2[2] - $col1[2]) * $i / $w;
                $this->SetFillColor($r, $g, $b);
                $this->Rect($x + $i, $y, 1, $h, 'F');
            }
        }
    }

    // Fungsi untuk menambahkan teks dengan latar belakang gradasi
    function TextWithBackground($x, $y, $teks) {
        // Warna latar belakang untuk gradasi (RGB)
        $col1 = [219, 159, 220];
        $col2 = [255, 255, 255];
        // Hitung lebar dan tinggi dari rectangle latar belakang
        $lebar = $this->GetStringWidth($teks) + 6;
        $tinggi = 10;
        // Gambar rectangle latar belakang gradasi
        $this->Gradient($x, $y, $lebar, $tinggi, $col1, $col2, 'horizontal');
        // Set warna teks
        $this->SetTextColor(158, 51, 51);
        // Set font
        $this->SetFont('Arial', 'B', 17);
        // Tambahkan teks
        $this->Text($x + 3, $y + 7, $teks);
    }

    // Fungsi untuk menambahkan paragraf dengan background
    function AddTextBox($x, $y, $w, $h, $teks) {
        // Warna latar belakang untuk gradasi (RGB)
        $col1 = [234, 176, 176];
        $col2 = [216, 191, 216];
        // Gambar rectangle latar belakang gradasi
        $this->Gradient($x, $y, $w, $h, $col1, $col2, 'horizontal');
        // Set warna teks
        $this->SetTextColor(0, 0, 0);
        // Set font
        $this->SetFont('Arial', '', 10);
        // Tambahkan teks
        $this->SetXY($x + 3, $y + 3);
        $this->MultiCell($w - 6, 5, $teks);
    }
}

// Buat instance dari FPDF
$pdf = new PDF();

// Tambahkan halaman
$pdf->AddPage();

// Coba gunakan jalur absolut untuk gambar 77.jpg
$imagePath = realpath('assets/img/77.jpg'); // Ganti dengan jalur absolut yang benar

if ($imagePath && file_exists($imagePath)) {
    $pdf->Image($imagePath, 40, 80, 120, 100); // Sesuaikan path, posisi, dan ukuran gambar yang diinginkan
} else {
    die('Gambar tidak ditemukan: ' . $imagePath);
}

// Tambahkan teks dengan background di bawah gambar
$pdf->TextWithBackground(70, 188, 'Madeline Emily'); // Sesuaikan posisi (x, y) dan teks sesuai kebutuhan

// Tambahkan paragraf dengan background di bawah teks "Madeline Emily"
$pdf->AddTextBox(20, 205, 170, 25, 'Laporan ini tidak dapat digunakan untuk mengidentifikasi status psikologis, kesehatan mental atau masalah kesehatan pada umumnya. Tanggung jawab atas tindakan keputusan yang dilakukan setelah membaca laporan ini sepenuhnya berada pada pengguna laporan. Di BINUS University, kami percaya bahwa keputusan masa depanmu ada di tanganmu sendiri.');

// Output PDF
$pdf->Output();
?>
