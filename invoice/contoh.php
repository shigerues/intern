<?php
require('../assets/fpdf/fpdf.php');

class PDF extends FPDF {
    // Header
    function Header() {
        $this->Image('../assets/img/logo telkomm.png', 10, 6, 30);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Minat & Bakat', 0, 1, 'C');
        $this->Ln(20);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->SetX(0);
        $this->Cell(0, 10, 'Chandra | beehive.binus.org | Page ' . $this->PageNo(), 0, 0, 0, 'C');
    }
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F') {
            $op = 'f';
        } elseif ($style == 'FD' || $style == 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }
        $MyArc = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));
        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
        $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
        $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
        $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
        $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }

    function DrawGraph() {
        $this->Line(50, 50, 50, 200); 
        $this->Line(50, 200, 180, 200);

        $labelsY = [
            'Realistic', 'Investigative', 'Artistic', 'Social', 
            'Enterprising', 'Conventional', 'Concern', 
            'Confidence', 'Conception', 'Control'
        ];

        for ($i = 0; $i < count($labelsY); $i++) {
            $this->SetXY(10, 55 + ($i * 13));
            $this->Cell(40, 10, $labelsY[$i], 0, 0, 'R');
        }

        $values = [3, 0, 2, 2, -2, -1, 2, 2, 2, -4];

        $colors = [
            [102, 178, 255], // Light Blue
            [255, 102, 102], // Light Red
            [255, 178, 102], // Light Orange
            [178, 255, 102], // Light Green
            [255, 255, 102], // Light Yellow
            [178, 102, 255], // Light Purple
            [255, 102, 178], // Light Pink
            [102, 255, 178], // Light Cyan
            [255, 204, 204]  // Light Salmon
        ];

        for ($i = 0; $i < count($values); $i++) {
            if ($labelsY[$i] == 'Investigative') {
                continue;
            }
            $barLength = $values[$i] * 10; 
            $barHeight = 8;
            $barYPos = 55 + ($i * 13);

            $color = $colors[$i % count($colors)];
            $this->SetFillColor($color[0], $color[1], $color[2]);

            if ($barLength < 0) {
                $this->RoundedRect(115 + $barLength, $barYPos, abs($barLength), $barHeight, 3, 'DF');
            } else {
                $this->RoundedRect(115, $barYPos, $barLength, $barHeight, 3, 'DF');
            }
        }

        $this->Line(115, 50, 115, 200); 
        $this->SetFont('Arial', '', 10);
        for ($i = -6; $i <= 6; $i++) {
            $this->SetXY(115 + ($i * 10) - 2, 202);
            $this->Cell(4, 4, $i, 0, 0, 'C');
        }
        $this->Ln(50);
    }

    function AddExplanation($explanation) {
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(180, 10, $explanation);
        $this->Ln(5);
    }

    function AddIconDescription($symbol, $title, $description) {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, $symbol . ' ' . $title, 0, 1);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(180, 5, $description);
        $this->Ln(5);
    }
}

$pdf = new PDF();

$pdf->AddPage();

$pdf->DrawGraph();

$explanation = "Kamu memiliki sifat pragmatis, konvensional, ekspresif, kreativitas, keramahan, tanggung jawab sosial yang cenderung digambarkan sebagai kecenderungan mekanis atau teknis, pendiam interpersonal, jujur, dan keras kepala dan kurang keterampilan sosial."
    . " Tipe seperti kamu menyukai pekerjaan seperti mekanik mobil, pengontrol pesawat, surveyor, petani, tukang listrik. Memiliki kemampuan mekanis, tetapi mungkin kurang keterampilan sosial."
    . " Sifat kamu Penyendiri, Taat Aturan, Jujur, Tulus, Keras Kepala, Kurang Fleksibel, Materialistis, Apa Adanya, Normal, Gigih, Praktis, Tidak Mencolok, Hemat, Wawasan Sempit dan Bodo Amat.";
$pdf->AddExplanation($explanation);

$pdf->AddIconDescription('⚙', 'The Doers', 'Kamu sangat senang memecahkan suatu masalah, terutama hal yang praktis dan masuk akal. Kamu menyukai pekerjaan yang berhubungan dengan tumbuhan, hewan, peralatan, dan mekanik.');
$pdf->AddIconDescription('🎨', 'The Creators', 'Kamu orang yang sangat romantis dan sensitif! Kamu tidak suka dibatasi karena kamu memiliki pemikiran yang abstrak, idealis, dan mempunyai banyak ide, namun tetap fleksibel dalam berbagai hal. Kamu juga bersifat ekspresif sehingga kamu bisa mudah bersahabat dengan siapapun, bahkan persahabatanmu bisa sampai langgeng, lho!');
$pdf->AddIconDescription('🤝', 'The Helpers', 'Kamu orang yang sangat humanis dan senang membantu orang lain. Kamu hangat dan ramah kepada orang lain sehingga tak heran kalau kamu senang melakukan kegiatan-kegiatan sosial yang bertujuan untuk kemajuan masyarakat. Kamu sangat pintar berkomunikasi dan bertanggung jawab, tetapi peduli dan sabar.');

$pdf->Output();
?>