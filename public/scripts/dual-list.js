document.addEventListener('DOMContentLoaded', () => {
    const avail    = document.getElementById('available');
    const sel      = document.getElementById('selected');
    const btnToSel = document.getElementById('to-selected');
    const btnToAv  = document.getElementById('to-available');

    function moveOptions(source, target) {
      Array.from(source.selectedOptions).forEach(opt => {
        target.appendChild(opt);
      });
    }

    avail.addEventListener('dblclick', () => moveOptions(avail, sel));
    sel.addEventListener('dblclick',   () => moveOptions(sel,  avail));

    btnToSel.addEventListener('click', () => moveOptions(avail, sel));
    btnToAv .addEventListener('click', () => moveOptions(sel,  avail));
  });
  