<?php
/*
 * Madilyn Carpenter
 * Date: August 23, 2026
 * Assignment: Module 11 PDF Programming Assignment
 * Purpose: This program connects to the Module 8 SpongeBob character
 * database, retrieves all character data, and creates a PDF report
 * containing general information and the database data in a table
 * with a header and footer.
 */

require("fpdf/fpdf.php");

// Connect to the database.
$connection = new mysqli("localhost", "student1", "pass", "baseball_01");

// Check the database connection.
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Retrieve all SpongeBob character records.
$sql = "SELECT character_id, character_name, species, occupation,
               home_location, age, favorite_food, is_main_character
        FROM spongebob_characters
        ORDER BY character_id";

$result = $connection->query($sql);

// Stop if the query fails.
if ($result === false) {
    die("Error retrieving database data: " . $connection->error);
}

// Create the PDF.
$pdf = new FPDF("L", "mm", "Letter");
$pdf->AddPage();
$pdf->SetTitle("Madilyn SpongeBob Character Database");

$pdf->SetFont("Arial", "B", 18);
$pdf->Cell(0, 12, "SpongeBob Character Database Report", 0, 1, "C");
$pdf->Ln(3);

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(0, 8, "General Information", 0, 1);

$pdf->SetFont("Arial", "", 10);
$information = "This report contains the SpongeBob character data stored in the "
             . "Module 8 database. The database includes each character's name, "
             . "species, occupation, home location, age, favorite food, and "
             . "whether the character is a main character.";
$pdf->MultiCell(0, 6, $information);
$pdf->Ln(5);

$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(0, 8, "SpongeBob Character Data", 0, 1);

$widths = [12, 38, 32, 48, 48, 18, 42, 32];

$pdf->SetFont("Arial", "B", 8);
$pdf->SetFillColor(220, 220, 220);

$headers = [
    "ID",
    "Character Name",
    "Species",
    "Occupation",
    "Home Location",
    "Age",
    "Favorite Food",
    "Main Character"
];

foreach ($headers as $i => $header) {
    $pdf->Cell($widths[$i], 8, $header, 1, 0, "C", true);
}
$pdf->Ln();

$pdf->SetFont("Arial", "", 8);
$recordCount = 0;

while ($row = $result->fetch_assoc()) {
    $recordCount++;

    $mainCharacter = ($row["is_main_character"] == 1) ? "Yes" : "No";

    $pdf->Cell($widths[0], 8, $row["character_id"], 1, 0, "C");
    $pdf->Cell($widths[1], 8, $row["character_name"], 1, 0, "L");
    $pdf->Cell($widths[2], 8, $row["species"], 1, 0, "L");
    $pdf->Cell($widths[3], 8, $row["occupation"], 1, 0, "L");
    $pdf->Cell($widths[4], 8, $row["home_location"], 1, 0, "L");
    $pdf->Cell($widths[5], 8, $row["age"], 1, 0, "C");
    $pdf->Cell($widths[6], 8, $row["favorite_food"], 1, 0, "L");
    $pdf->Cell($widths[7], 8, $mainCharacter, 1, 0, "C");
    $pdf->Ln();
}

$pdf->SetFont("Arial", "B", 9);
$pdf->Cell(0, 8, "Total Characters: " . $recordCount, 1, 1, "R");

$connection->close();

$pdf->Output("I", "MadilynSpongeBobDatabase.pdf");
?>