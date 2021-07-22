function onRatingHover(event)
{
    let elem = event.target.parentElement.firstElementChild;
    while (elem !== event.target.nextElementSibling) {
        elem.className = "bi bi-star-fill";
        elem = elem.nextElementSibling;
    }

    if (event.target.nextElementSibling != null) {
        event.target.nextElementSibling.className = "bi bi-star";
    }
    // while (elem !== null) {
    //     elem.className = "bi bi-star-fill";
    //     elem = elem.nextElementSibling;
    // }

    // console.log(event);
}

function onRatingOut(event)
{
    let elem = event.target.firstElementChild;
    while (elem != null) {
        elem.className = "bi bi-star";
        elem = elem.nextElementSibling;
    }
}