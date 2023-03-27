window.onscroll = () => {
    toggleTopButton();
}

function scrollToTop(){
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function toggleTopButton() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById('back-to-up').classList.remove('d-none');
    } else {
        document.getElementById('back-to-up').classList.add('d-none');
    }
}

const accordionBtns = document.querySelectorAll(".accordion");

accordionBtns.forEach((accordion) => {
    accordion.onclick = function () {
        this.classList.toggle("is-open");

        let content = this.nextElementSibling;
        console.log(content);

        if (content.style.maxHeight) {
        //this is if the accordion is open
        content.style.maxHeight = null;
        } else {
        //if the accordion is currently closed
        content.style.maxHeight = content.scrollHeight + "px";
        console.log(content.style.maxHeight);
        }
    };
});

const filterContainer = document.querySelector(".event-filter"),
eventItems = document.querySelectorAll(".event-item");

if(filterContainer){
    filterContainer.addEventListener("click", (event) =>{
        if(event.target.classList.contains("filter-item")){
            // deactivate existing active 'filter-item'
            filterContainer.querySelector(".active").classList.remove("active");
            // activate new 'filter-item'
            event.target.classList.add("active");
            const filterValue = event.target.getAttribute("data-filter");
            eventItems.forEach((item) =>{
                if(item.classList.contains(filterValue) || filterValue === 'all'){
                    item.classList.remove("hide");
                    item.classList.add("show");
                } else {
                    item.classList.remove("show");
                    item.classList.add("hide");
                }
            });
        }
    });
}