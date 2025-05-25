<?php
// Model/interfaces/iAppointmentActions.php
namespace Model\interfaces;

interface iAppointmentActions {
    /**
     * Schedule a new appointment
     * 
     * @param int $doctorId
     * @param int $patientId
     * @param string $appointmentDate
     * @param int $status
     * @return int|bool New appointment ID or false on failure
     */
    public function scheduleAppointment($doctorId, $patientId, $appointmentDate, $status = 0);
    
    /**
     * Cancel an appointment
     * 
     * @param int $appointmentId
     * @return bool Success status
     */
    public function cancelAppointment($appointmentId);
    
    /**
     * Reschedule an appointment
     * 
     * @param int $appointmentId
     * @param string $newAppointmentDate
     * @return bool Success status
     */
    public function rescheduleAppointment($appointmentId, $newAppointmentDate);
    
    /**
     * Update appointment status
     * 
     * @param int $appointmentId
     * @param int $newStatus
     * @return bool Success status
     */
    public function updateAppointmentStatus($appointmentId, $newStatus);
}