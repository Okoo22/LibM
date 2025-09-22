<?php
// Calculate fine for a book based on due date, return date, and status
function calculateFine($due_date, $return_date, $status) {
    $fine = 0;
    $today = date("Y-m-d"); // current date

    // Case 1: Lost book → flat fine
    if ($status === "lost") {
        $fine = 500;
    }
    // Case 2: Overdue book
    else {
        // Determine which date to compare with due date
        $checkDate = $return_date ? $return_date : $today;

        $due = new DateTime($due_date);
        $check = new DateTime($checkDate);

        if ($check > $due) {
            $interval = $due->diff($check);
            $daysLate = $interval->days;
            $fine = $daysLate * 10; // ₹10 per day late
        }
    }

    return $fine;
}
?>
