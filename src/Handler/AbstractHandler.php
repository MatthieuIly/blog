<?php

namespace App\Handler;

use App\Event\ReverseEvent;
use App\Event\TransferEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractHandler implements HandlerInterface
{
    private EventDispatcherInterface $eventDispatcher;

    private FormFactoryInterface $formFactory;

    protected FormInterface $form;

    abstract protected function getDataTransfertObject(): object;

    abstract protected function getFormType(): string;

    abstract protected function process(mixed $data): void;

    /**
     * @required
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @required
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    public function handle(Request $request, object $originalData, array $options = []): bool
    {
        $data = $this->getDataTransfertObject();

        $this->eventDispatcher->dispatch(new TransferEvent($originalData, $data), TransferEvent::NAME);

        $this->form = $this->formFactory->create($this->getFormType(), $data, $options)->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->eventDispatcher->dispatch(new ReverseEvent($data, $originalData), ReverseEvent::NAME);

            $this->process($originalData);

            return true;
        }

        return false;
    }


    public function createView(): FormView
    {
        return $this->form->createView();
    }
}
