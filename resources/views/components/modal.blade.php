<div>
    {{-- MODAL --}}
    <div id="myModal" class="modal" onclick="closeModal(event)">
        <span class="close" onclick="closeModal(event)">&times;</span>
        <div class="modal-content-wrapper">
            <a id="modalLink" href="#" target="_blank">
                <img class="modal-content" id="img01">
            </a>
            <div id="caption"></div>
        </div>
    </div>
</div>
<script>
    function openModal(img) {
        const modal = document.getElementById("myModal");
        const modalImg = document.getElementById("img01");
        const captionText = document.getElementById("caption");
        const modalLink = document.getElementById("modalLink");

        modal.style.display = "block";
        modalImg.src = img.src;
        captionText.innerHTML = img.alt;
        modalLink.href = img.src;
    }

    function closeModal() {
        var modal = document.getElementById('myModal');
        modal.style.display = "none";
    }
</script>
