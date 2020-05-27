import { ReactElement } from "react";
import Link from "next/link";
import { useStoreState } from "../../model/helpers/hooks";

const TaskSuggestionsForVolonteers: React.FunctionComponent = (): ReactElement => {
  const { isLoggedIn, isTaskAuthorLoggedIn } = useStoreState(
    (state) => state.session
  );
  const { nextTaskSlug, approvedDoer } = useStoreState(
    (state) => state.components.task
  );
  const isUserCandidate = false;

  return (
    isLoggedIn &&
    !isTaskAuthorLoggedIn && (
      <>
        {!approvedDoer && !isUserCandidate && (
          <div className="task-give-response">
            <p>
              Кликнув на кнопку, вы попадете в список волонтёров откликнувшихся
              на задачу. Заказчик задачи выберет подходящего из списка.
            </p>
            <a
              href="#"
              className="button-give-response"
              onClick={(event) => event.preventDefault()}
            >
              Откликнуться на задачу
            </a>
          </div>
        )}

        {nextTaskSlug && (
          <div className="task-get-next">
            <p>Хочешь посмотреть ещё подходящих для тебя задач?</p>
            <Link href="/tasks/[slug]" as={`/tasks/${nextTaskSlug}`}>
              <a>Следующая задача</a>
            </Link>
          </div>
        )}
      </>
    )
  );
};

export default TaskSuggestionsForVolonteers;
