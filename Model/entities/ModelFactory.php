<?php
// Model/entities/ModelFactory.php
namespace Model\entities;

class ModelFactory
{
    public static function getModelInstance($tableName, $db)
    {
        $map = [
            'users' => 'User',
            'patients' => 'Patient',
            'doctors' => 'Doctor',
            'staff' => 'Staff',
            'appointments' => 'Appointment',
            'medications' => 'Medication',
            'medical_history' => 'MedicalHistory',
            'audit_logs' => 'AuditLog',
            'services' => 'Service',
            'service_types' => 'ServiceType',
            'units' => 'Unit',
            'usertype' => 'UserType',
            'user_type_pages' => 'UserTypePage',
            'words' => 'Word',
            'translations' => 'Translation',
            'translation_details' => 'TranslationDetail',
            'payment_methods' => 'PaymentMethod',
            'payment_method_options' => 'PaymentMethodOption',
            'payment_method_values' => 'PaymentMethodValue',
            'payment_options' => 'PaymentOption',
            'payment_values' => 'PaymentValue',
            'positions' => 'Position',
            'render_payment_methods' => 'RenderPaymentMethod',

            // Add more entities as needed
        ];
        if (!isset($map[$tableName])) {
            $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
            $trace = print_r($bt, true);
            throw new \Exception("[ModelFactory] Unknown table/entity: '$tableName'\nBacktrace:\n$trace");
        }
        $className = __NAMESPACE__ . "\\" . $map[$tableName];
        if (!class_exists($className)) {
            $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
            $trace = print_r($bt, true);
            throw new \Exception("[ModelFactory] Class not found for entity: '$className' (table: '$tableName')\nBacktrace:\n$trace");
        }
        return new $className($db);
    }
}