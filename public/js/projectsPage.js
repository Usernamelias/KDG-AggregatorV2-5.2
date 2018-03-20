document.querySelector("input[data-search-box='search']").addEventListener("keyup", event => {
    if(event.key !== "Enter") return;
    document.querySelector("data-search-box='submit'").click();
    event.preventDefault();
});

