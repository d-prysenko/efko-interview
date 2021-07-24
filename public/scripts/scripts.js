function onRatingHover(event)
{
    let rating = Array.prototype.indexOf.call(event.target.parentNode.children, event.target) + 1;

    event.target.parentElement.setAttribute("selected-rating", rating.toString());

    let elems = event.target.parentElement.children;

    for (let i = 0; i < 5; i++) {
        if (i < rating) {
            elems[i].className = "bi bi-star-fill";
        } else {
            elems[i].className = "bi bi-star";
        }
    }

    // while (elem !== null) {
    //     elem.className = "bi bi-star-fill";
    //     elem = elem.nextElementSibling;
    // }

    // console.log(event);
}

function onRatingOut(event)
{
    let rating = event.target.getAttribute("rating");
    let elems = event.target.children;

    for (let i = 0; i < 5; i++) {
        if (i < rating) {
            elems[i].className = "bi bi-star-fill";
        } else {
            elems[i].className = "bi bi-star";
        }
    }

    // while (elem != null) {
    //     elem.className = "bi bi-star";
    //     elem = elem.nextElementSibling;
    // }
    event.target.removeAttribute("selected-rating");
}

async function onRatingClick(event)
{
    let elem = ((event.target.tagName == 'I') ? event.target.parentElement : event.target );
    let new_rating = elem.getAttribute("selected-rating");

    if (new_rating != null) {
        let id = elem.parentNode.parentNode.firstElementChild.innerText
        elem.setAttribute("rating", new_rating);

        let response = await fetch('/rating-update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: "problem_id=" + id + "&mark=" + new_rating
        });

        if (response.status != 200) {
            console.error("Ошибка " + response.status);
        }
    }

    // console.log(id + ": " + new_rating);
}

async function deleteEntry(event, uid)
{
    let row = document.getElementById('r-' + uid);
    // let row = event.target.parentElement.parentElement.parentElement.parentElement.parentElement;
    let id = row.firstElementChild.innerText;
    let response = await fetch('/delete-entry', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: "problem_id=" + id
    });

    // if (response.status != 200) {
    //     console.error("Ошибка " + response.status);
    // }

    if (response.status == 200) {
        entities.removeChild(row);
    }

    console.log(id);
}