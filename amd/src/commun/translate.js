import { translateContent } from "local_digitalta/repositories/translation_repository";

import * as Str from 'core/str';

export const translateButton = () => {
  const translateButton = document.querySelector(".translate-action");
  if (!translateButton) {
    return;
  }
  const translatedButtonIcon = translateButton.querySelector("i");
  translateButton.addEventListener("click", async () => {
    const translatableContent = document.querySelectorAll(".translatable-content");
    if (translateButton.dataset.translated === "true") {
      translatableContent.forEach((content) => {
        content.classList.remove("d-none");
      });

      const translatedContent = document.querySelectorAll(".translated-content");
      translatedContent.forEach((content) => {
        content.remove();
      });

      translateButton.dataset.translated = "false";
      translateButton.innerHTML = await Str.get_string('seetranslation', 'local_digitalta');
      translateButton.insertAdjacentElement("beforeend", translatedButtonIcon);
    } else {
      translateButton.dataset.translated = "true";
      const promises = [];

      for (const content of translatableContent) {
        const { response } = await translateContent({ text: content.innerHTML.trim() });
        promises.push(createElementPromise(content, response));
      }
      await Promise.all(promises);
      translateButton.innerHTML = await Str.get_string('seeoriginal', 'local_digitalta');
      translateButton.insertAdjacentElement("beforeend", translatedButtonIcon);
    }
  });
};

const createElementPromise = (translatableContent, text) => {
  return new Promise((resolve) => {
    const newElement = document.createElement(translatableContent.tagName.toLowerCase());
    newElement.classList.add("translated-content");
    newElement.innerHTML = text;
    translatableContent.classList.add("d-none");
    translatableContent.insertAdjacentElement("afterend", newElement);
    resolve();
  });
};
