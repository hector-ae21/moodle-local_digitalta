export const generatePagination = (totalPages, currentPage, maxVisible = 7) => {
  let pages = [];

  const addPage = (page, selected = false) => {
    pages.push({
      page: page,
      selected: selected,
      isEllipsis: false,
    });
  };

  const addEllipsis = () => {
    pages.push({
      page: null,
      selected: false,
      isEllipsis: true,
    });
  };

  if (totalPages <= maxVisible) {
    for (let i = 1; i <= totalPages; i++) {
      addPage(i, i === currentPage);
    }
  } else if (currentPage <= 4) {
    for (let i = 1; i <= maxVisible - 2; i++) {
      addPage(i, i === currentPage);
    }
    addEllipsis();
    addPage(totalPages, currentPage === totalPages);
  } else if (currentPage >= totalPages - 3) {
    addPage(1, currentPage === 1);
    addEllipsis();
    for (let i = totalPages - (maxVisible - 3); i <= totalPages; i++) {
      addPage(i, i === currentPage);
    }
  } else {
    addPage(1);
    addEllipsis();
    const middleRange = Math.floor((maxVisible - 4) / 2);
    const start = currentPage - middleRange;
    const end = currentPage + middleRange;

    for (let i = start; i <= end; i++) {
      addPage(i, i === currentPage);
    }

    addEllipsis();
    addPage(totalPages);
  }

  return pages;
};