<div class="govuk-grid-row">
    <div class="govuk-grid-column-full">
        <?php
        global $measure_activity, $application;
        $application->get_measure_condition_codes();
        $application->get_measure_actions();
        new table_control($measure_activity->condition_list, "condition_table", "<p class='govuk-body' style='margin-top:2em;margin-bottom:2em'>There are currently no conditions assigned to the current activity(s).</p>", "condition_code");
        ?>
        <form action="measure_activity_actions.php" class="data_entry_form" method="post" novalidate>
            <?php

            new select_control(
                "Add a measure condition",
                $label_style = "govuk-label--m for_measure_condition",
                $hint_text = "Please select the condition code from the list below.",
                $control_name = "condition_code",
                $dataset = $application->measure_condition_codes,
                $default_value = 0,
                $default_string = "-- Please select a condition code --",
                $default_on_insert = "",
                $selected = "",
                $required = true,
                $disabled_on_edit = false,
                $group_by = "",
                $custom_errors = "",
                $group_class = "",
                $control_class = "condition_mechanic_measure_condition_code"
            );

            new input_control(
                $label = "Enter the reference price (where applicable)",
                $label_style = "govuk-label",
                $hint_text = "",
                $control_name = "reference_price",
                $control_style = "govuk-input govuk-input--width-30 condition_mechanic_reference_duty duty",
                $size = 100,
                $maxlength = 100,
                $pattern = "",
                $required = "required",
                $default = "",
                $default_on_insert = "",
                $disabled = false,
                $custom_errors = "",
                $group_class = "reference_price_group"
            );

            new input_control(
                $label = "Select the certificate, licence or document",
                $label_style = "govuk-label",
                $hint_text = "",
                $control_name = "certificate",
                $control_style = "govuk-input condition_mechanic_certificate certificate govuk-details--overflow",
                $size = 100,
                $maxlength = 100,
                $pattern = "",
                $required = false,
                $default = "",
                $default_on_insert = "",
                $disabled = false,
                $custom_errors = "",
                $group_class = "certificate_group"
            );

            new select_control(
                $label = "What action should take place in response?",
                $label_style = "govuk-label for_measure_action",
                $hint_text = "",
                $control_name = "action_code",
                $dataset = $application->measure_actions,
                $default_value = 0,
                $default_string = "-- Please select an action code --",
                $default_on_insert = "",
                $selected = "",
                $required = true,
                $disabled_on_edit = false,
                $group_by = "",
                $custom_errors = "",
                $group_class = "action_code_group",
                $control_class = "condition_mechanic_measure_action_code"
            );

            new hidden_control(
                $control_name = "action",
                $value = "add_condition"
            );

            new radio_control(
                $label = "Will the applicable duty be common to all permutations in this activity?",
                $label_style = "govuk-fieldset__legend--s",
                $hint_text = "Please identify if the duties for this condition are common to all permutations specified on the previous screen.",
                $control_name = "applicable_duty_permutation",
                $dataset = $application->conditional_duty_application_options,
                $selected = null,
                $radio_control_style = "stacked_detail",
                $required = true,
                $disabled_on_edit = false,
                $custom_errors = "",
                $group_class = "applicable_duty_permutation"
            );

            new input_control(
                $label = "Enter the applicable duty",
                $label_style = "govuk-fieldset__legend--s",
                $hint_text = "",
                $control_name = "applicable_duty",
                $control_style = "govuk-input govuk-input--width-30 condition_mechanic_applicable_duty duty",
                $size = 100,
                $maxlength = 100,
                $pattern = "",
                $required = "required",
                $default = "",
                $default_on_insert = "",
                $disabled = false,
                $custom_errors = "",
                $group_class = "applicable_duty"
            );

            new button_control("Add condition", "add_condition", "secondary", true, "");
            ?>
        </form>
    </div>
</div>