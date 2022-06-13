<?php

namespace Drupal\fbl\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Field Based Login Configuration Form.
 *
 * @package Drupal\fbl\Form
 */
class FblConfiguration extends ConfigFormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory object.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_manager
   *   The entity field manager.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    EntityFieldManagerInterface $entity_manager,
    Connection $database
  ) {
    parent::__construct($config_factory);
    $this->entityFieldManager = $entity_manager;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_field.manager'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'fbl_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['fbl.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Getting the configuration value.
    $default_value_config = $this->config('fbl.settings');
    $default_value = $default_value_config->get('field_based_login');
    $form['field_based_login'] = [
      '#type' => 'details',
      '#title' => $this->t('Field based login settings'),
      '#tree' => TRUE,
      '#open' => TRUE,
    ];
    $entity_type_id = 'user';
    $bundle = 'user';
    $bundleFields = [];
    foreach ($this->entityFieldManager->getFieldDefinitions($entity_type_id, $bundle) as $field_name => $field_definition) {
      if (!empty($field_definition->getTargetBundle())) {
        if ($field_definition->getType() == 'string' || $field_definition->getType() == 'integer' || $field_definition->getType() == 'telephone') {
          $bundleFields[$field_name] = $field_definition->getLabel();
        }
      }
    }
    $form['field_based_login']['field'] = [
      '#type' => 'select',
      '#title' => $this->t('Unique field'),
      '#options' => $bundleFields,
      '#empty_option' => '- Select -',
      '#default_value' => $default_value['field'] ?? '',
      '#description' => $this->t('Unique field to allow users to login with this field. Note : Selected field will become unique filed.'),
    ];

    $form['field_based_login']['allow_user_name'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow login with username'),
      '#default_value' => $default_value['allow_user_name'] ?? 1,
    ];

    $form['field_based_login']['allow_user_email'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow login with E-mail'),
      '#default_value' => $default_value['allow_user_email'] ?? '',
    ];

    $form['field_based_login']['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User login form - User name field Label'),
      '#default_value' => $default_value['label'] ?? '',
      '#description' => $this->t('Ex: Phone / E-mail'),
      '#size' => 60,
      '#maxlength' => 60,
    ];

    // Added description field for configuration.
    $form['field_based_login']['field_desc'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User login form - User name field Description'),
      '#default_value' => $default_value['field_desc'] ?? '',
      '#description' => $this->t('Ex: Provide description for custom login field'),
      '#size' => 60,
      '#maxlength' => 60,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $field_name = $form_state->getValue(['field_based_login', 'field']);
    $allow_user_login_by_name = $form_state->getValue([
      'field_based_login',
      'allow_user_name',
    ]);
    if (isset($field_name) && empty($field_name) && ($allow_user_login_by_name == 0)) {
      $form_state->setErrorByName('field_based_login][field', $this->t('Please select any one of the option to login'));
    }
    $user_count = $this->getUserCount();
    if (isset($field_name) && !empty($field_name)) {
      $field_data_count = $this->getFieldDataCount($field_name);
    }
    $entity_type_id = 'user';
    $bundle = 'user';
    $is_required = FALSE;
    foreach ($this->entityFieldManager->getFieldDefinitions($entity_type_id, $bundle) as $field_name_value => $field_definition) {
      if (!empty($field_definition->getTargetBundle())) {
        if ($field_name_value == $field_name) {
          $is_required = $field_definition->isRequired();
        }
      }
    }
    if (!empty($field_name)) {
      // Field is required check.
      if (!$is_required) {
        $this->messenger()
          ->addWarning($this->t('Selected field is not mandatory.'));
      }
      // Check to see if any users are missing the data for this field.
      if ($user_count > $field_data_count) {
        $this->messenger()
          ->addWarning($this->t('Some of the users are missing data for the selected field.'));
      }
      // Check for duplicates.
      if ($this->checkForDuplicates($field_name)) {
        $form_state->setErrorByName('field_based_login][field', $this->t('Selected field is not unique. Duplicates found. Please select another field.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('fbl.settings');
    foreach (Element::children($form) as $variable) {
      if ($variable == 'field_based_login') {
        $data = $form_state->getValue($form[$variable]['#parents']);
        $data['label'] = strip_tags($data['label']);
        $data['field_desc'] = strip_tags($data['field_desc']);
        $config->set($variable, $data);
      }
      else {
        $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
      }
    }
    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * Returns number of users.
   *
   * @return string
   *   Returns user count.
   */
  protected function getUserCount() {
    $query = $this->database->select('users', 'u');
    $query->fields('u', ['uid']);
    $query->condition('u.uid', '0', '!=');
    return $query->countQuery()->execute()->fetchField();
  }

  /**
   * Helper function to count.
   *
   * @param string $field_name
   *   Machine name of the user account field.
   *
   * @return int
   *   Returns how many fields having value
   */
  protected function getFieldDataCount($field_name) {
    $table_name = 'user__' . $field_name;
    $table_column = $field_name . '_value';
    $query = $this->database->select($table_name, 'field_value');
    $query->fields('field_value', [$table_column]);
    $query->condition('field_value.bundle', 'user');
    return $query->countQuery()->execute()->fetchField();
  }

  /**
   * Helper function to check duplicate records of user data.
   *
   * @param string $field_name
   *   Machine name of the user account field.
   */
  protected function checkForDuplicates($field_name) {
    $table_name = 'user__' . $field_name;
    $table_column = $field_name . '_value';
    $query = $this->database->select($table_name, 't');
    $query->fields('t', [$table_column]);
    $query->groupBy('t.' . $table_column);
    $query->condition('t.bundle', 'user');
    $query->addExpression('COUNT(' . $table_column . ')', 'field_count');
    $query->range(0, 1);
    $duplicate_count = $query->execute()->fetchAll();
    $count = 0;
    foreach ($duplicate_count as $count) {
      $count = $count->field_count;
    }
    if ($count > 1) {
      return TRUE;
    }
    return FALSE;
  }

}
