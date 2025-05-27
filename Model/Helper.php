<?php

class Helper
{
    /**
     * Sanitize input data
     */
    public static function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate email format
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validate password strength
     */
    public static function validatePassword($password)
    {
        // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
        return strlen($password) >= 8 &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password);
    }

    /**
     * Generate pagination links
     */
    public static function paginate($total, $page, $perPage, $url)
    {
        $totalPages = ceil($total / $perPage);
        $links = [];

        if ($totalPages > 1) {
            // Previous page
            if ($page > 1) {
                $links[] = [
                    'url' => $url . '?page=' . ($page - 1),
                    'text' => 'Previous',
                    'active' => false
                ];
            }

            // Page numbers
            for ($i = 1; $i <= $totalPages; $i++) {
                $links[] = [
                    'url' => $url . '?page=' . $i,
                    'text' => $i,
                    'active' => $i === $page
                ];
            }

            // Next page
            if ($page < $totalPages) {
                $links[] = [
                    'url' => $url . '?page=' . ($page + 1),
                    'text' => 'Next',
                    'active' => false
                ];
            }
        }

        return $links;
    }
    /**
     * Get current language
     */
    public static function getCurrentLanguage()
    {
        return $_SESSION['language'] ?? DEFAULT_LANGUAGE;
    }

    /**
     * Set language
     */
    public static function setLanguage($language)
    {
        if (in_array($language, AVAILABLE_LANGUAGES)) {
            $_SESSION['language'] = $language;
            return true;
        }
        return false;
    }

    /**
     * Get translation
     */
    public static function translate($key, $params = [])
    {
        $language = self::getCurrentLanguage();
        $translations = require APP_ROOT . "/languages/{$language}.php";

        $text = $translations[$key] ?? $key;

        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $text = str_replace(":{$param}", $value, $text);
            }
        }

        return $text;
    }

    /**
     * Get translations
     */
    public static function getTranslations($db, $context, $language)
    {
        if ($context === 'appointment_form') {
            return self::getAppointmentTranslations($db, $language);
        }
        return [];
    }

    private static function getAppointmentTranslations($db, $language)
    {
        $query = "SELECT t.translation_key, td.translation_value 
                 FROM Translation t 
                 JOIN Translation_Details td ON t.ID = td.translationID 
                 WHERE t.context = 'appointment_form' 
                 AND td.language = ?";

        try {
            $stmt = $db->prepare($query);
            $stmt->execute([$language]);
            $translations = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            if (empty($translations)) {
                return self::getFallbackTranslations($language);
            }

            return $translations;
        } catch (PDOException $e) {
            error_log("Error fetching translations: " . $e->getMessage());
            return self::getFallbackTranslations($language);
        }
    }

    private static function getFallbackTranslations($language)
    {
        $fallbackTranslations = [
            'en' => [
                'select_doctor' => 'Select Doctor',
                'appointment_date' => 'Appointment Date',
                'appointment_time' => 'Appointment Time',
                'appointment_reason' => 'Reason for Visit',
                'book_appointment' => 'Book Appointment',
                'cancel' => 'Cancel',
                'appointment_success' => 'Appointment booked successfully',
                'appointment_error' => 'Failed to book appointment',
                'appointment_cancelled' => 'Appointment cancelled successfully',
                'appointment_cancel_error' => 'Failed to cancel appointment',
                'invalid_appointment' => 'Invalid appointment',
                'no_appointments' => 'You don\'t have any appointments yet',
                'confirm_cancel' => 'Are you sure you want to cancel this appointment?'
            ],
            'ar' => [
                'select_doctor' => 'اختر الطبيب',
                'appointment_date' => 'تاريخ الموعد',
                'appointment_time' => 'وقت الموعد',
                'appointment_reason' => 'سبب الزيارة',
                'book_appointment' => 'حجز موعد',
                'cancel' => 'إلغاء',
                'appointment_success' => 'تم حجز الموعد بنجاح',
                'appointment_error' => 'فشل في حجز الموعد',
                'appointment_cancelled' => 'تم إلغاء الموعد بنجاح',
                'appointment_cancel_error' => 'فشل في إلغاء الموعد',
                'invalid_appointment' => 'موعد غير صالح',
                'no_appointments' => 'ليس لديك أي مواعيد بعد',
                'confirm_cancel' => 'هل أنت متأكد أنك تريد إلغاء هذا الموعد؟'
            ]
        ];

        return $fallbackTranslations[$language] ?? $fallbackTranslations['en'];
    }
}