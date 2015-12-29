var modal = $("#changemodal");
var wrapper = $("#changemodal-wrapper");
var modalOpen = false;

$("#close-button")
    .addEventListener("click", function(event) {
        closeChangelogModal();
    });

function listener(evt) {
    evt = evt || window.event;
    if (evt.keyCode == 27 && modalOpen) {
        closeChangelogModal();
    }
}

function openChangelogModal() {
    setTimeout(function(){
        modalOpen = true;
    }, 300);
    wrapper.style.display = 'block';
    modal.style.display = 'block';
    wrapper._.setAttribute("class", "animated fadeIn");
    modal._.setAttribute("class", "animated zoomIn");
    window.addEventListener('keyup', listener, false);
}

function closeChangelogModal() {
    setTimeout(function(){
        wrapper.style.display = 'none';
        modal.style.display = 'none';
        modalOpen = false;
    }, 300);
    wrapper._.setAttribute("class", "animated fadeOut");
    modal._.setAttribute("class", "animated zoomOut");
    window.removeEventListener('keyup', listener, false);
}

function toggleChangelogModal() {
    if(modalOpen) {
        closeChangelogModal();
    } else {
        openChangelogModal();
    }
}

(function() {
    document.addEventListener('keyup', listener, false);
    document.addEventListener("click", function (e) {
        var level = 0;
        for (var element = e.target; element; element = element.parentNode) {
            if (element.id === 'changemodal') { return; }
            level++;
        }
        if(modalOpen) closeChangelogModal();
    });
})();