function validateForm() {
    var deadline = document.getElementById("deadline").value;
    var today = new Date();
    var minDate = new Date(today.getTime() + 14 * 24 * 60 * 60 * 1000); // 14 days from today

    var deadlineError = document.getElementById("deadlineError");
    deadlineError.innerHTML = "";

    if (deadline === "") {
        deadlineError.innerHTML = "Please select a deadline.";
        return false;
    }

    var selectedDate = new Date(deadline);

    if (selectedDate < today) {
        deadlineError.innerHTML = "Deadline cannot be in the past.";
        return false;
    }

    if (selectedDate < minDate) {
        deadlineError.innerHTML = "Please allow at least 2 weeks for us to complete the order.";
        return false;
    }

    return true;
}

function toggleAdditionalDomain() {
    var domainType = document.getElementById("domain_type").value;
    var additionalDomain = document.getElementById("additionalDomain");

    if (domainType === "other") {
        additionalDomain.style.display = "block";
        additionalDomain.setAttribute("required", "true");
    } else {
        additionalDomain.style.display = "none";
        additionalDomain.removeAttribute("required");
    }
}