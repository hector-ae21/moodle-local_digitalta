
/**
 * Set the events for the module.
 * @return {void}
 */
function setEvents() {
  document.querySelectorAll('.section').forEach(section => {
    var collapseButton = section.querySelector('.header');
    //eslint-disable-next-line no-console
    console.log(collapseButton);

    if (collapseButton) {
      collapseButton.addEventListener('click', () => {
        var collapseIcon = collapseButton.querySelector('i');
        var sectionContent = section.querySelector('.questions');
        if (collapseButton.classList.contains('collapsed')) {
          collapseButton.classList.remove('collapsed');
          collapseIcon.classList.remove('fa-chevron-down');
          collapseIcon.classList.add('fa-chevron-right');
          sectionContent.style.display = 'none';
        } else {
          collapseButton.classList.add('collapsed');
          collapseIcon.classList.remove('fa-chevron-right');
          collapseIcon.classList.add('fa-chevron-down');
          sectionContent.style.display = 'flex';
        }
      });
    }
});


  document.querySelectorAll('#importer').forEach(importer => {
    var addButton = importer.querySelector('#add_button');
    var importerDiv = importer.querySelector('#import_div');

    //eslint-disable-next-line no-console
    console.log(addButton);
    //eslint-disable-next-line no-console
    console.log(importerDiv);

    // Add Button Clic Event
    addButton.addEventListener('click', () => {

        importerDiv.style.display = importerDiv.style.display == 'flex' ? 'none' : 'flex';
        //eslint-disable-next-line no-console
        console.log("Add Button Clicked");
    });

    importerDiv.querySelectorAll('.import_button').forEach(importButton => {
      importButton.addEventListener('click', () => {
        var buttonId = importButton.id;

        switch (buttonId) {
          case "tiny":
            //eslint-disable-next-line no-console
            console.log("Tiny");
            break;
          case "tiny_record":
            //eslint-disable-next-line no-console
            console.log("Tiny Record");
            break;
          case "import_cases":
            //eslint-disable-next-line no-console
            console.log("Import Cases");
            break;
          case "import_experiences":
            //eslint-disable-next-line no-console
            console.log("Import Experiences");
            break;
          case "import_tutor_conc":
            //eslint-disable-next-line no-console
            console.log("Import Tutor Conc");
            break;
          case "import_resources":
            //eslint-disable-next-line no-console
            console.log("Import Resources");
            break;
        }
      });
    });
  });
}

export const init = () => {
    setEvents();
  };