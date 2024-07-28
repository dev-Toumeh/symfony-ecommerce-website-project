document.addEventListener('DOMContentLoaded', function() {
    const sorter = document.getElementById('basic');
    sorter.addEventListener('change', function() {
        const criteria = sorter.value;
        sortItems(criteria);
    });
});

function sortItems(criteria) {
    const itemsContainer = document.querySelector('.items-container');
    const items = Array.from(itemsContainer.getElementsByClassName('list-view-box'));

    let sortFunction;

    switch (criteria) {
        case '1':
            sortFunction = (a, b) => getAttribute(b, 'popularity') - getAttribute(a, 'popularity');
            break;
        case '2':
            sortFunction = (a, b) => getAttribute(b, 'price') - getAttribute(a, 'price');
            break;
        case '3':
            sortFunction = (a, b) => getAttribute(a, 'price') - getAttribute(b, 'price');
            break;
        case '4':
            sortFunction = (a, b) => getAttribute(b, 'best-selling') - getAttribute(a, 'best-selling');
            break;
        default:
            sortFunction = null;
    }

    if (sortFunction) {
        items.sort(sortFunction);
        items.forEach(item => itemsContainer.appendChild(item));
    }
}

function getAttribute(element, attribute) {
    return parseFloat(element.querySelector('[style*="display: none"]').getAttribute(attribute));
}
