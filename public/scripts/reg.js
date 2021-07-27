function validateEmail(email)
{
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

let is_name = false,
    is_surname = false,
    is_email = false,
    is_password = false,
    is_role = false;

function changeSubmitState()
{
    if (is_name && is_surname && is_email && is_password && is_role) {
        submit_btn.removeAttribute('disabled');
    } else {
        submit_btn.setAttribute('disabled', '');
    }
}

i_name.oninput = function (e) {
    is_name = e.target.value.length >= 2;

    changeSubmitState();
}

surname.oninput = function (e) {
    is_surname = e.target.value.length >= 2;

    changeSubmitState();
}

email.oninput = function (e) {
    is_email = validateEmail(e.target.value);

    changeSubmitState();
}

password.oninput = function (e) {
    is_password = e.target.value.length >= 8;

    changeSubmitState();
}

role.oninput = function (e) {
    is_role = e.target.value.length != 0;

    changeSubmitState();
}
