export const getVideoURL = (videoName) => {
  const youtubeBaseURL = "https://www.youtube-nocookie.com/embed/";
  const options = "?autoplay=1&rel=0";
  let videoId = "";
  switch (videoName) {
    case "experience-video":
      videoId = "BlZtijkFvKI";
      break;
    case "cases-video":
      videoId = "xnU1Y6PUlb8";
      break;
    case "resources-video":
      videoId = "ld-CXSl7mU4";
      break;
    case "themes-tag-video":
      videoId = "6PNQstOuQmc";
      break;
    case "tutor-mentor-video":
      videoId = "mNxgDzTkzpQ";
      break;
    case "tutor-teacher-video":
      videoId = "rGyyPtcb17o";
      break;
    case "chat-video":
      videoId = "cXMMKEKpsCk";
      break;
    default:
      break;
  }
  return youtubeBaseURL + videoId + options;
};
