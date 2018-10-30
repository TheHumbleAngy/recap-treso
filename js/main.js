let nombre = $('#nombre'),
    nature_ = $('#nature'),
    valider = $('#valider'),
    choix = '';

let monnaie, id_banque, id_pays;

$(document).ready(function () {
    $('#nature').prop('disabled', true);
    $('#nombre').prop('disabled', true);
    $('#saisir').prop('disabled', true);

    $('#valider').prop('disabled', true);
});

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction()
};

function scrollFunction() {
    if ($(document).scrollTop > 20 || document.documentElement.scrollTop > 20) {
        $('#goTop').css("display", "block");
    } else {
        $('#goTop').css("display", "none");
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    $('html').animate({scrollTop: 0}, 'slow');
}

function separateurMilliers(nStr) {
    // To pass the value as a string
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? ',' + x[1] : '';
    let rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ' ' + '$2');
    }

    return x1 + x2;
}

function fusionMilliers(str) {
    str = str.toString().replace(' ', '');
    str = str.includes(',') ? str.replace(',', '.') : str;

    return str;
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

function selectionBanque(selector) {
    let banque,
        pays,
        id_monnaie,
        str = $(selector).attr('id');
    let arr = str.split("_");

    banque = arr[1];
    pays = arr[2];
    monnaie = arr[3];

    id_banque = arr[5];
    id_pays = arr[6];
    id_monnaie = arr[7];

    let text = banque + ' ' + pays + ' - ' + monnaie;
    $('.titre').html(text);

    $.ajax({
        type: 'POST',
        url: 'banques/ajax_banque_info.php',
        data: {
            banque: id_banque,
            pays: id_pays,
            monnaie: id_monnaie
        },
        success: function (result) {
            json_data = JSON.parse(result);
            let entite = json_data[0].entite,
                solde_xof = json_data[0].solde_xof,
                solde_devise = json_data[0].solde_devise,
                sign = "";

            switch (monnaie) {
                case "USD":
                    sign = '<i class="fas fa-dollar-sign text-uppercase"></i>';
                    break;
                case "EURO":
                    sign = '<i class="fas fa-euro-sign text-uppercase"></i>';
                    break;
                default:
                    sign = '<strong><span class="text-uppercase">' + monnaie + '</span></strong>';
            }

            $('#monnaie_devise').html(sign);
            $('#entite').html('#' + entite);
            $('#nature').prop('disabled', false);
            $('#nombre').prop('disabled', false);
            $('#saisir').prop('disabled', false);

            $('#solde_xof').attr('value', separateurMilliers(solde_xof.toFixed(2)));
            $('#solde_devise').attr('value', separateurMilliers(solde_devise.toFixed(2)));
            $('#idbanque').html(id_banque);
            $('#feedback').empty();

            valider.prop('disabled', true);
        }
    });
}

$("[id*='menu_']").click(function () {
    selectionBanque(this);
});

function afficherSaisieOperations() {
    let n = nombre.val(),
        nature = nature_.val();
    let feedback = $('#feedback');

    if (n !== '' && nature !== 'Sélectionner...') {
        $.ajax({
            type: 'POST',
            url: 'operations/ajax_saisie_ope.php',
            data: {
                nbr: n,
                nature: nature
            },
            success: function (resultat) {
                feedback.empty();
                feedback.html(resultat);
                valider.prop('disabled', false);
            }
        })
    }
}

function isEmpty(element) {
    if (element === '')
        return true;
}

function validationCheck(n) {
    for (let i = 0; i < n; i++) {

    }
}

function ajoutBanque() {
    let libelle_banque = $('#libelle').val().trim(),
        pays_banque = $('#pays').val().trim(),
        entite_banque = $('#entite').val().trim(),
        monnaie_banque = $('#monnaie').val().trim(),
        info, action;

    if (libelle_banque === '' || pays_banque === '' || monnaie_banque === '' || entite_banque === '') {
        $('#modal-check').modal('show');
    }
    else {
        info = "libelle_banque=" + libelle_banque +
            "&pays_banque=" + pays_banque +
            "&entite_banque=" + entite_banque +
            "&monnaie_banque=" + monnaie_banque;
        action = "ajout_banque";

        $.ajax({
            type: 'POST',
            url: 'banques/update_data_banques.php?action=' + action,
            data: info,
            success: function (data) {
                $('#content-response').html(data);
                $('#form_banque').trigger('reset');
                $('#modal-response').modal('show');
            }
        });
    }
}

function ajoutOperation() {
    let compte_op = [],
        libelle_op = [],
        datesaisie_op = new Date(),
        date_op = [],
        designation_op = [],
        cours_op = [],
        devise_op = [],
        xof_op = [],
        statut = [],
        observation_op = [],
        nature = $("#nature").val(),
        nbr = $("#nbr_").text();

    // Initialisation de la date de saisie
    datesaisie_op = datesaisie_op.getFullYear() + "-" + (datesaisie_op.getMonth() + 1) + "-" + datesaisie_op.getDate();

    // Recuperation des infos pour chaque operation (ligne) saisie
    let i, k = 0,
        compte_,
        libelle_,
        date_,
        designation_,
        cours_,
        devise_,
        xof_,
        statut_,
        observation_;

    for (i = 0; i < nbr; i++) {
        compte_ = $('[id*="compte"]')[i].value.trim();
        libelle_ = $('[id*="libelle"]')[i].value.trim();
        date_ = $('[id*="date"]')[i].value.trim();
        designation_ = $('[id*="operation"]')[i].value.trim();
        cours_ = Number(fusionMilliers($('[id*="cours-"]')[i].value.trim()));
        devise_ = Number(fusionMilliers($('[id*="mtt_devise-"]')[i].value.trim()));
        xof_ = Number(fusionMilliers($('[id*="mtt_xof-"]')[i].value.trim()));
        statut_ = $('[id*="statut"]')[i].value;
        observation_ = $('[id*="observation"]')[i].value.trim();

        if (compte_ !== '' && libelle_ !== '' && designation_ !== '' && devise_ !== '' && cours_ !== '' && date_ !== '') {
            compte_op[k] = compte_;
            libelle_op[k] = libelle_;
            date_op[k] = date_;
            designation_op[k] = designation_;
            cours_op[k] = cours_;
            devise_op[k] = devise_;
            xof_op[k] = xof_;
            statut[k] = statut_;
            observation_op[k] = observation_;
            k++;
        }
    }

    let json_compte_op = JSON.stringify(compte_op),
        json_libelle_op = JSON.stringify(libelle_op),
        json_date_op = JSON.stringify(date_op),
        json_designation_op = JSON.stringify(designation_op),
        json_cours_op = JSON.stringify(cours_op),
        json_devise_op = JSON.stringify(devise_op),
        json_xof_op = JSON.stringify(xof_op),
        json_statut_op = JSON.stringify(statut),
        json_observation_op = JSON.stringify(observation_op),
        info = "nbr=" + k +
            "&id_banque=" + id_banque +
            "&id_type_operation=" + nature +
            "&compte_operation=" + json_compte_op +
            "&tag_operation=" + json_libelle_op +
            "&date_saisie_operation=" + datesaisie_op +
            "&date_operation=" + json_date_op +
            "&designation_operation=" + json_designation_op +
            "&cours_operation=" + json_cours_op +
            "&montant_operation=" + json_devise_op +
            "&montant_xof_operation=" + json_xof_op +
            "&statut_operation=" + json_statut_op +
            "&observation_operation=" + json_observation_op,
        action = "ajout_operation";
    $.ajax({
        type: 'POST',
        url: 'operations/update_data_operations.php?action=' + action + '&monnaie=' + monnaie + '&pays=' + id_pays,
        data: info,
        success: function (data) {
            $('#content-response').html(data);
            $('#feedback').empty();
            $('#modal-response').modal('show');
            valider.prop('disabled', true);
            selectionBanque();
        }
    });
}

function consultationOperation() {
    let debut = $('#debut').val(),
        fin = $('#fin').val(),
        entite = $('#entite').val();

    choix = $("[name='rdo_nature']:checked").val();

    $.ajax({
        type: 'POST',
        url: 'operations/ajax_consult_ope.php',
        data: {
            debut: debut,
            fin: fin,
            entite: entite,
            choix: choix
        },
        success: function (data) {
            $('#feedback_consultation').html(data);
            let solde_avant = $('#solde_avt').val(),
                solde_apres = $('#solde_apr').val();

            solde_avant = (solde_avant === "") ? 0 : solde_avant;
            solde_apres = (solde_apres === "") ? 0 : solde_apres;

            $('#solde_avant').val(solde_avant);
            $('#solde_apres').val(solde_apres);
        }
    })
}

$('#param_entite').change(function () {
    let param = $(this).val();

    $.ajax({
        type: 'POST',
        url: 'operations/ajax_param_consult_ope.php',
        data: {
            param: param
        },
        success: function (data) {
            $('#details').html(data);
        }
    })
});

$('#proceder').click(function () {
    let param = $('#param_entite').val();

    if (param !== 'Sélectionner...') {
        $.ajax({
            type: 'POST',
            url: 'operations/form_consult_ope.php',
            data: {
                param: param
            },
            success: function (data) {
                $('#content').html(data);
            }
        })
    }
});

function retourParam() {
    $.ajax({
        type: 'POST',
        url: 'operations/param_consult_ope.php',
        success: function (data) {
            $('#content').html(data);
        }
    })
}

function majStatut(element) {
    let id_ = Object.values(element)[0].parentElement.id;
    let statut = element.value;
    let arr = id_.split('_');
    let id = arr[1];
    let action = 'maj_statut';
    let info = 'id=' + id + '&statut=' + statut;

    $.ajax({
        type: 'POST',
        url: 'operations/update_data_operations.php?action=' + action,
        data: info,
        success: function (data) {
            console.log(data);
        }
    });
}

function calculXof(element) {
    let arr = element.id.split('-');
    let i = arr[1];

    let sel_mtt_devise = '#mtt_devise-' + i,
        sel_cours = '#cours-' + i,
        sel_mtt_xof = '#mtt_xof-' + i;

    let devise = $(sel_mtt_devise).val(),
        cours = $(sel_cours).val();
    if (devise === '' || cours === '') {
        console.log('One of devise or cours value is empty');
    } else {
        $(sel_mtt_xof).val(devise * cours);
    }

}