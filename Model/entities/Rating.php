<?php
namespace Model\entities;

use Model\abstract\AbstractModel;

class Rating extends AbstractModel
{
    public $ID;
    public $appointmentID;
    public $doctorID;
    public $userID;
    public $rating;
    public $comment;
    public $createdAt;
    public $updatedAt;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->tableName = 'Rating';
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO Rating (appointmentID, doctorID, userID, rating, comment, createdAt, updatedAt)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->bind_param(
            "iiids",
            $data['appointmentID'],
            $data['doctorID'],
            $data['userID'],
            $data['rating'],
            $data['comment']
        );

        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // Update doctor's average rating
            $this->updateDoctorRating($data['doctorID']);
        }

        return $result;
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("
            SELECT r.*, 
                   CONCAT(u.FirstName, ' ', u.LastName) as patientName,
                   CONCAT(d.FirstName, ' ', d.LastName) as doctorName
            FROM Rating r
            JOIN Users u ON r.userID = u.userID
            JOIN Doctors d ON r.doctorID = d.ID
            WHERE r.ID = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rating = $result->fetch_assoc();
        $stmt->close();
        return $rating;
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("
            UPDATE Rating 
            SET rating = ?, comment = ?, updatedAt = NOW()
            WHERE ID = ?
        ");

        $stmt->bind_param("dsi", $data['rating'], $data['comment'], $id);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // Get doctor ID for updating average rating
            $rating = $this->read($id);
            if ($rating) {
                $this->updateDoctorRating($rating['doctorID']);
            }
        }

        return $result;
    }

    public function delete($id)
    {
        // Get doctor ID before deleting
        $rating = $this->read($id);
        $doctorId = $rating ? $rating['doctorID'] : null;

        $stmt = $this->conn->prepare("DELETE FROM Rating WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();

        if ($result && $doctorId) {
            $this->updateDoctorRating($doctorId);
        }

        return $result;
    }

    public function getDoctorRatings($doctorId)
    {
        $stmt = $this->conn->prepare("
            SELECT r.*, 
                   CONCAT(u.FirstName, ' ', u.LastName) as patientName,
                   DATE_FORMAT(r.createdAt, '%M %d, %Y') as formattedDate
            FROM Rating r
            JOIN Users u ON r.userID = u.userID
            WHERE r.doctorID = ?
            ORDER BY r.createdAt DESC
        ");

        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ratings = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $ratings;
    }

    public function getUserRating($appointmentId)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM Rating 
            WHERE appointmentID = ?
        ");

        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $rating = $result->fetch_assoc();
        $stmt->close();
        return $rating;
    }

    public function getByAppointment($appointmentId)
    {
        $stmt = $this->conn->prepare("
            SELECT r.*, 
                   CONCAT(u.FirstName, ' ', u.LastName) as patientName,
                   CONCAT(d.FirstName, ' ', d.LastName) as doctorName,
                   DATE_FORMAT(r.createdAt, '%M %d, %Y') as formattedDate
            FROM Rating r
            JOIN Users u ON r.userID = u.userID
            JOIN Doctors d ON r.doctorID = d.ID
            WHERE r.appointmentID = ?
        ");

        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $rating = $result->fetch_assoc();
        $stmt->close();
        return $rating;
    }

    public function getByDoctor($doctorId)
    {
        $stmt = $this->conn->prepare("
            SELECT r.*, 
                   CONCAT(u.FirstName, ' ', u.LastName) as patientName,
                   DATE_FORMAT(r.createdAt, '%M %d, %Y') as formattedDate
            FROM Rating r
            JOIN Users u ON r.userID = u.userID
            WHERE r.doctorID = ?
            ORDER BY r.createdAt DESC
        ");

        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ratings = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $ratings;
    }

    private function updateDoctorRating($doctorId)
    {
        $stmt = $this->conn->prepare("
            UPDATE Doctors d
            SET d.rating = (
                SELECT AVG(rating)
                FROM Rating
                WHERE doctorID = ?
            )
            WHERE d.ID = ?
        ");

        $stmt->bind_param("ii", $doctorId, $doctorId);
        $stmt->execute();
        $stmt->close();
    }
}