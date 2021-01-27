<?php
// fonction pour la récupération des services de l'hotel en cas d'erreur du formulaire d'ajout d'hotel
if (!function_exists('get_services_posted')) {
    function get_services_posted($key)
    {
        if (isset($_POST['services_hotel'])) {
            if (in_array($key, $_POST['services_hotel'])) {
                echo "checked";
            } else {
                echo " ";
            }
        }
    }
}

// fonction pour la modification des services de l'hotel dans modifier hotel
if (!function_exists('get_services_modify')) {
    function get_services_modify($key, $posted)
    {
        if (isset($_POST['services_hotel'])) {
            if (in_array($key, $_POST['services_hotel'])) {
                echo "checked";
            } else {
                echo " ";
            }
        } elseif (in_array($key, $posted)) {
            echo "checked";
        } else {
            echo " ";
        }
    }
}

// récupérer les champs postées en cas d'erreur d'ajout offre
if (!function_exists('get_input_posted')) {
    function get_input_posted($key)
    {
        if (isset($_POST[$key])) echo $_POST[$key];
    }
}

// récupérer les champs postées en cas d'erreur d'ajout offre plus la valeur adresse du site
if (!function_exists('get_input_posted_site')) {
    function get_input_posted_site($key)
    {
        if (isset($_POST[$key])) {
            echo $_POST[$key];
        }else{
            echo "https://example.com";
        }
    }
}
