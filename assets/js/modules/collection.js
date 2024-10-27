document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete_item_link').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('li').remove();
        });
    });

    document.querySelectorAll('.add_item_link').forEach(btn => {
        btn.addEventListener("click", addFormToCollection);
    });
});

function addFormToCollection(e) {
    const collectionHolderClass = e.currentTarget.dataset.collectionHolderClass;
    const collectionHolder = document.querySelector('.' + collectionHolderClass);

    if (!collectionHolder) {
        console.error(`Collection holder with class '${collectionHolderClass}' not found.`);
        return;
    }

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    item.innerHTML += '<button type="button" class="delete_item_link">Delete</button>';
    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    item.querySelector('.delete_item_link').addEventListener('click', function(e) {
        e.preventDefault();
        this.closest('li').remove();
    });
}