$(document).ready(function () {
    function modalCallBack() {
        $('.modal-toggle').on('click', function (e) {
            e.preventDefault();
            $('.modal').toggleClass('is-visible');
        });
    }
    modalCallBack();
});