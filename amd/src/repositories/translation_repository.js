import Ajax from "core/ajax";

export const translateContent = (args) => {
  const request = {
    methodname: "local_digitalta_translate_text",
    args: args,
  };
  return Ajax.call([request])[0];
};