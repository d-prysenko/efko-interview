function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

let is_name = false,
    is_surname = false,
    is_email = false,
    is_password = false,
    is_role = false;

function changeSubmitState() {
    if (is_name && is_surname && is_email && is_password && is_role) {
        submit_btn.removeAttribute('disabled');
    } else {
        submit_btn.setAttribute('disabled', '');
    }
}

i_name.oninput = function (e) {
    if (e.target.value.length >= 2) {
        is_name = true;
    } else {
        is_name = false;
    }

    changeSubmitState();
}

surname.oninput = function (e) {
    if (e.target.value.length >= 2) {
        is_surname = true;
    } else {
        is_surname = false;
    }

    changeSubmitState();
}

email.oninput = function (e) {
    if (validateEmail(e.target.value)) {
        is_email = true;
    } else {
        is_email = false;
    }

    changeSubmitState();
}

password.oninput = function (e) {
    if (e.target.value.length >= 8) {
        is_password = true;
    } else {
        is_password = false;
    }

    changeSubmitState();
}

role.oninput = function (e) {
    if (e.target.value.length != 0) {
        is_role = true;
    } else {
        is_role = false;
    }

    changeSubmitState();
}

