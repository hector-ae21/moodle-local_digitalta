import {exception as displayException} from 'core/notification';
import Templates from 'core/templates';
import $ from 'jquery';
import Ajax from 'core/ajax';
let pages = 0;

let filters = [];
let selectedPage = 1;
export const init = () => {
    getAndRenderFilters();
    getExperiences();

    $('.dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });

    $(document).on("click", "li.page-item.page-link.page", function() {
        const clickedPage = $(this).attr('attr-page');
        if (selectedPage !== clickedPage) {
            selectedPage = parseInt(clickedPage);
            getExperiences();
        }
    });
    $(document).on("click", "li.page-item.page-link.state", function() {
        const state = $(this).attr('aria-label');
        if (state === 'Next') {
            const posibleNextPage = selectedPage + 1;
            if (posibleNextPage > 0 && posibleNextPage <= pages) {
                selectedPage++;
                getExperiences();
            }
        } else if (state === 'Previous') {
            const posiblePreviousPage = selectedPage - 1;
            if (posiblePreviousPage > 0 && posiblePreviousPage <= pages) {
                selectedPage--;
                getExperiences();
            }
        }
    });

    $(document).on("change", ".filterThemeSelect", function() {
        const selectedValue = $("option:selected.enable", this).val();
        const selectedText = $("option:selected.enable", this).text();
        if (selectedText.length > 0) {
            const element = $("option:selected.enable", this);
            element.addClass('disabled');
            element.removeClass('enable');
            const filterObject = {"type": "theme", "value": {id: selectedValue, name: selectedText}};
            setFilter(filterObject);
            $(".filterThemeSelect option:first").prop("selected", true);
            $(element).prop('disabled', true);
        }
    });

    $(document).on("change", ".filterLanguageSelect", function() {
        const selectedText = $("option:selected.enable", this).text();
        if (selectedText.length > 0) {
            const element = $("option:selected.enable", this);
            element.addClass('disabled');
            element.removeClass('enable');
            const filterObject = {"type": "language", "value": selectedText};
            setFilter(filterObject);
            $(".filterLanguageSelect option:first").prop("selected", true);
            $(element).prop('disabled', true);
        }
    });

    $(document).on("keydown", "#inlineFormInputGroup", function(event) {
        // Verificamos si la tecla Enter fue presionada (keyCode 13 o event.which 13)
        if (event.key === "Enter" || event.keyCode === 13) {
            const filterText = $(this).val(); // Obtenemos el valor del input
            const filterObject = {"type": "tag", "value": filterText};
            setFilter(filterObject);
            $(this).val(''); // Limpiamos el input
        }
    });

    $(document).on("keydown", "#authorFilters", async function() {
        const filterText = $(this).val(); // Obtenemos el valor del input
        const request = {
            methodname: 'core_enrol_get_potential_users',
            args: {courseid: 1, enrolid: 1, page: 0, perpage: 10, search: filterText, searchanywhere: true}
        };
        const response = await Ajax.call([request])[0];

        if (response.length === 0) {
            $("#suggestionsAuthors").empty();
        } else {
            const authorsSuggestions = await Templates.renderForPromise('local_digitalta/_common/listAuthors', {users: response});
            Templates.replaceNodeContents("#suggestionsAuthors", authorsSuggestions.html, authorsSuggestions.js);
        }
    });

    $("#suggestionsTags").on("click", ".tag-item", function() {
        const filterText = {type: "tag", value: $(this).attr('attr-name')};
        setFilter(filterText);
        $("#inlineFormInputGroup").val("");
    });

    $("#suggestionsAuthors").on("click", ".author-item", function() {
        const filterText = {type: "author", value: {id: $(this).attr('attr-id-user'), name: $(this).attr('attr-name')}};
        setFilter(filterText);
        $("#authorFilters").val("");
    });


    $("#filters-menu").click(function() {
        if ($(".tagsInputFilter").is(":focus")) {
            $("#suggestionsAuthors").hide();
            $("#suggestionsTags").show();
        } else if ($("#authorFilters").is(":focus")) {
            $("#suggestionsTags").hide();
            $("#suggestionsAuthors").show();
        } else {
            $("#suggestionsTags").hide();
            $("#suggestionsAuthors").hide();
        }
    });

    $(document).on("click", "#removeFilter", function() {
        const filterText = $(this).attr('attr-filter');
        const filterType = $(this).attr('attr-type');
        const filterObject = {"type": filterType, "value": filterText};
        removeFilter(filterObject);
    });
};

const getExperiences = async() => {
    // Remapeamos la data por el caso especial de authores
    const mappedFilters = filters.map((filter) => {
        if (filter.type === 'author' || filter.type === 'theme') {
            return {type: filter.type, value: filter.value.id};
        }
        return filter;
    });

    const request = {
        methodname: 'local_digitalta_experiences_get_by_pagination',
        args: {
            'pagenumber': selectedPage,
            'filters': JSON.stringify(mappedFilters)
        }
    };
    const response = await Ajax.call([request])[0];

    if (response.data.length === 0) {
        Templates.renderForPromise('local_digitalta/_common/empty-view', response).then(({html, js}) => {
            Templates.replaceNodeContents("#list-experience-body", html, js);
        }).catch((error) => displayException(error));
        $(".digitalta.pagination").hide();
    } else {
        let obj = {"experiences": response};
        let paginationArray = generatePagination(response.pages, selectedPage);
        const experienceList = await Templates.renderForPromise('local_digitalta/experiences/dashboard/experience-list', obj);
        const pagination = await Templates.renderForPromise("local_digitalta/_common/pagination", {
            pages: paginationArray,
            pagesCount: paginationArray.length,
            currentPage: selectedPage,
            disablePrevious: selectedPage <= 1,
            disableNext: selectedPage >= pages,
          });
        Templates.replaceNodeContents("#list-experience-body", experienceList.html, experienceList.js);
        Templates.replaceNodeContents("#digital-pagination", pagination.html, pagination.js);
        $(".digitalta.pagination").show();
    }
};

const generatePagination = (totalPages, selectedPage) => {
    let pagination = [];
    for (let i = 0; i < totalPages; i++) {
        let page = {
            page: i + 1,
            selected: i + 1 === selectedPage
        };
        pagination.push(page);
    }
    return pagination;
};

const getAndRenderFilters = async() => {
    const themesRequest = {
        methodname: 'local_digitalta_themes_get',
        args: {}
    };
    const tagsRequest = {methodname: 'local_digitalta_tags_get', args: {}};
    const languagesRequest = {methodname: 'local_digitalta_experiences_get_used_langs', args: {}};
    const themesResponse = await Ajax.call([themesRequest])[0];
    const tagsResponse = await Ajax.call([tagsRequest])[0];
    const languagesResponse = await Ajax.call([languagesRequest])[0];

    // eslint-disable-next-line max-len
    const templateFilterThemes = await Templates.renderForPromise('local_digitalta/_common/filterTheme', {"themes": themesResponse});
    // eslint-disable-next-line max-len
    const templateFilterLanguages = await Templates.renderForPromise('local_digitalta/_common/filterLanguages', {"languages": languagesResponse});
    Templates.replaceNodeContents("#filterThemeSelect", templateFilterThemes.html, templateFilterThemes.js);
    Templates.replaceNodeContents("#filterLanguageSelect", templateFilterLanguages.html, templateFilterLanguages.js);

    const availableTags = tagsResponse.map(function(tag) {
        return tag.name;
    });

    // Evento de entrada en el campo de texto
    $('#inlineFormInputGroup').on('input', async function() {
        var input = $(this).val().toLowerCase();
        var suggestions = availableTags.filter(function(tag) {
            return tag.toLowerCase().startsWith(input);
        });

        const templateFilterTags = await Templates.renderForPromise('local_digitalta/_common/listTags', {tags: suggestions});
        Templates.replaceNodeContents("#suggestionsTags", templateFilterTags.html, templateFilterTags.js);
    });
};

const setFilter = (filterObject) => {
    filters.push(filterObject);
    selectedPage = 1;
    renderFilters();
};

const removeFilter = (filterObject) => {
    const index = filters.findIndex((filter) => {
        if (filter.type === 'author' || filter.type === 'theme') {
            return filter.type === filterObject.type && filter.value.name === filterObject.value;
        } else {
            return filter.type === filterObject.type && filter.value === filterObject.value;
        }
    });

    if (index > -1) {
        if (filterObject.type === 'theme') {
            let themeSelect = $("#filterThemes");
            let option = $('option[value="' + filters[index].value.id + '"].disabled', themeSelect);
            option.removeClass('disabled');
            option.addClass('enable');
            $("#filterThemes option:first").prop("selected", true);
            $(option).prop('disabled', false);
        } else if (filterObject.type === 'language') {
            let langSelect = $(".filterLanguageSelect");
            let option = $('option[value="' + filterObject.value + '"].disabled', langSelect);
            option.removeClass('disabled');
            option.addClass('enable');
            $(".filterLanguageSelect option:first").prop("selected", true);
            $(option).prop('disabled', false);
        }
        filters.splice(index, 1);
        selectedPage = 1;
        renderFilters();
    }
};

const renderFilters = () => {
    getExperiences();
    const filtersMap = filters.map((filter) => {
        if (filter.type === 'author' || filter.type === 'theme') {
            return {type: filter.type, value: filter.value.name};
        } else {
            return {type: filter.type, value: filter.value};
        }
    });
    Templates.renderForPromise('local_digitalta/_common/filterList', {filters: filtersMap}).then(({html, js}) => {
        Templates.replaceNodeContents("#filtersList", html, js);
    }).catch((error) => displayException(error));
};