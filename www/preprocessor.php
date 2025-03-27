<?php session_start(); ?>

<?php include "header.php"; ?>

<h3>The Preprocessor</h3>

<p class="testo-grande">
    <i>
        The preprocessor take in input a configuration json and some templates so that it generates a CRUD web application
    </i>
</p>

<p class="testo-grande">
    <i>
        The button "Validate and format" validate and format the configuration json
    </i>
</p>
<p class="testo-grande">
    <i>
        The button "Validate and submit" validate and generate in output the source code
    </i>
</p>


<div class="testo-grande">
    <form action="parse.php" method="post" spellcheck="false">
    <div>
        <label for="schema" class="form-label"><i>Json</i></label>
        <?php
        $schema = <<<END
{
    "app_name": "Demo App",
    "tables": [
        {
            "name": "category",
            "plural_name": "categories",
            "html_form_label": "Category form",
            "html_list_label": "Category table",
            "id": "id",
            "fields": [
                {
                    "name": "id",
                    "label": "#",
                    "html_type": "text",
                    "data_type": "number",
                    "validators": []
                },
                {
                    "name": "name",
                    "label": "Name",
                    "html_type": "text",
                    "data_type": "string",
                    "validators": ["required","maxlength","notUnique"]
                },
                {
                    "name": "description",
                    "label": "Description",
                    "html_type": "textarea",
                    "data_type": "text",
                    "validators": ["maxlength"]
                }
            ],
            "subtables": [
                {
                    "name": "product",
                    "alias": "products_of_category",
                    "inverse_ref": "category_id"
                }
            ]
        },
        {
            "name": "product",
            "plural_name": "products",
            "html_form_label": "Product form",
            "html_list_label": "Products table",
            "id": "id",
            "fields": [
                {
                    "name": "id",
                    "label": "#",
                    "html_type": "text",
                    "data_type": "number",
                    "validators": []
                },
                {
                    "name": "name",
                    "label": "Name",
                    "html_type": "text",
                    "data_type": "string",
                    "validators": ["required","maxlength","notUnique"]
                },
                {
                    "name": "availability",
                    "label": "Availability",
                    "html_type": "checkbox",
                    "data_type": "boolean",
                    "validators": []
                },
                {
                    "name": "price",
                    "label": "Price",
                    "html_type": "text",
                    "data_type": "float",
                    "validators": ["pattern"]
                },
                {
                    "name": "expiration",
                    "label": "Expiration",
                    "html_type": "date",
                    "data_type": "date",
                    "validators": []
                },
                {
                    "name": "category_id",
                    "label": "Category",
                    "html_type": "select",
                    "data_type": "number",
                    "validators": [],
                    "ref": {
                        "table_ref": "category",
                        "id_ref": "id",
                        "label_ref": "name"
                    }
                }
            ]
        },
        {
            "name": "provider",
            "plural_name": "providers",
            "html_form_label": "Provider form",
            "html_list_label": "Providers table",
            "id": "id",
            "fields": [
                {
                    "name": "id",
                    "label": "#",
                    "html_type": "text",
                    "data_type": "number",
                    "validators": []
                },
                {
                    "name": "name",
                    "label": "Name",
                    "html_type": "text",
                    "data_type": "string",
                    "validators": ["required","maxlength","notUnique"]
                },
                {
                    "name": "category_id",
                    "label": "Categories",
                    "html_type": "select",
                    "data_type": "number",
                    "validators": [],
                    "ref": {
                        "table_ref": "category",
                        "id_ref": "id",
                        "label_ref": "name",
                        "multiple_table_ref": "category_of_provider"
                    }
                }
            ]
        },
        {
            "name": "category_of_provider",
            "plural_name": "categories_of_providers",
            "html_form_label": "Category of provider form",
            "html_list_label": "Categories of providers table",
            "id": "id",
            "fields": [
                {
                    "name": "id",
                    "label": "#",
                    "html_type": "text",
                    "data_type": "number",
                    "validators": []
                },{
                    "name": "provider_id",
                    "label": "Provider",
                    "html_type": "select",
                    "data_type": "number",
                    "validators": [],
                    "ref": {
                        "table_ref": "provider",
                        "id_ref": "id",
                        "label_ref": "name"
                    }
                },
                {
                    "name": "category_id",
                    "label": "Category",
                    "html_type": "select",
                    "data_type": "number",
                    "validators": [],
                    "ref": {
                        "table_ref": "category",
                        "id_ref": "id",
                        "label_ref": "name"
                    }
                }
            ]
        }
    ]
}
END;
        if (isset($_SESSION["old"]["schema"])) {
            $schema = json_encode(json_decode($_SESSION["old"]["schema"]), JSON_PRETTY_PRINT);
        }
        if ("null" === $schema) {
            $schema = $_SESSION["old"]["schema"];
        }
        ?>
        <textarea id="schema" name="schema" rows="20"><?php echo $schema; ?></textarea>
        <?php if (isset($_SESSION["errors"]["schema"])): ?>
            <ul>
                <?php foreach ($_SESSION["errors"]["schema"] as $error): ?>
                <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <input type="submit" name="submit" value="Validate and format">
    <input type="submit" name="submit" value="Validate and submit">
    <br>


    </form>
</div>


<?php include "footer.php"; ?>

<?php session_destroy(); ?>
