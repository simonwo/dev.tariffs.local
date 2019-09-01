<?php
    $title = "Backup";
    require ("includes/db.php");
	require ("includes/header.php");
	?>
			<div class="app-content__header">
				<h1 class="govuk-heading-xl">Backup database</h1>
			</div>
			<div class="app-prose-scope">
			<form action="/actions/backup_actions.html" method="post">
			<div class="govuk-form-group">
  <fieldset class="govuk-fieldset" aria-describedby="sign-in-hint">
    
    <span id="sign-in-hint" class="govuk-hint">
      Please select the type of backup that you would like to take:
    </span>
    <div class="govuk-radios">
	  
	<div class="govuk-radios__item">
        <input class="govuk-radios__input" id="backup_type_complete" name="backup_type" type="radio" value="complete" aria-describedby="backup_type_complete-item-hint">
        <label class="govuk-label govuk-radios__label govuk-label--s" for="backup_type_complete">
          Complete backup
        </label> <span id="backup_type_complete" class="govuk-hint govuk-radios__hint">
          Backs up all structure and data from all schemas
        </span> </div>
		
		<div class="govuk-radios__item">
        <input class="govuk-radios__input" id="backup_type_public" name="backup_type" type="radio" value="public" aria-describedby="backup_type_public-item-hint">
        <label class="govuk-label govuk-radios__label govuk-label--s" for="backup_type_public">
          Public schema only
        </label> <span id="backup_type_public" class="govuk-hint govuk-radios__hint">
          Backs up all structure and data from the public
        </span> </div>
		
		<div class="govuk-radios__item">
        <input class="govuk-radios__input" id="backup_type_ml" name="backup_type" type="radio" value="ml" aria-describedby="backup_type_ml-item-hint">
        <label class="govuk-label govuk-radios__label govuk-label--s" for="backup_type_ml">
          ML schema only
        </label> <span id="backup_type_ml" class="govuk-hint govuk-radios__hint">
          Backs up all structure and data from the ML schema
        </span> </div>
		
		<div class="govuk-radios__item">
        <input class="govuk-radios__input" id="backup_type_schema" name="backup_type" type="radio" value="schema" aria-describedby="backup_type_schema-item-hint">
        <label class="govuk-label govuk-radios__label govuk-label--s" for="backup_type_schema">
          Just the structure
        </label> <span id="backup_type_schema" class="govuk-hint govuk-radios__hint">
          Backs up only the structure of both schemas
        </span> </div>
    </div>
  </fieldset>
</div>
<input type="hidden" name="phase" id="phase" value="perform_backup" />
<button type="submit" class="govuk-button">
  Start backup
</button>
</form>


		</div>
</div>

<?php
	require ("includes/footer.php")
?>