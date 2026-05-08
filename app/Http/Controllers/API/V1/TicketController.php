<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Events\TicketCreated;
use App\Events\TicketUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException; // Imported as dependency

class TicketController extends Controller
{
    /**
     * Shared validation rules for store and update methods. 
     * This ensures consistency across the API.
     */
    private function ticketRules(): array
    {
        return [
            'customer_name' => 'required|string|max:200',
            'email'         => 'required|email',
            'description'   => 'required|string',
            'phone'         => 'nullable|string|max:20',
            'category_id'   => 'nullable|exists:categories,id',
            'status'        => 'nullable|integer|in:0,1,2,3',
        ];
    }

    /**
     * Get all tickets with pagination.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->query('per_page', 15);
            $tickets = Ticket::with(['comments', 'comments.user'])
                ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $tickets->items(),
                'pagination' => [
                    'total' => $tickets->total(),
                    'per_page' => $tickets->perPage(),
                    'current_page' => $tickets->currentPage(),
                    'last_page' => $tickets->lastPage(),
                    'from' => $tickets->firstItem(),
                    'to' => $tickets->lastItem(),
                ],
                'message' => 'Tickets retrieved successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving tickets: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single ticket by ID.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $ticket = Ticket::with(['comments', 'comments.user', 'category'])->find($id);

            if (!$ticket) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ticket not found.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $ticket,
                'message' => 'Ticket retrieved successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created ticket.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // 1. Unified Validation
            $validated = $request->validate($this->ticketRules());

            // 2. Prepare Data
            $ref = sha1(time() . $validated['email']);
            $ticket = Ticket::create(array_merge($validated, [
                'ref'    => $ref,
                'status' => 0,
            ]));

            // 3. Trigger Event
            TicketCreated::dispatch($ticket);

            return response()->json([
                'status'  => 'success',
                'data'    => $ticket,
                'message' => 'Ticket created successfully. Reference: ' . $ref
            ], 201);
        } catch (ValidationException $e) { 
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error creating ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing ticket.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $ticket = Ticket::find($id);

            if (!$ticket) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ticket not found.'
                ], 404);
            }

            // 1. Unified Validation 
            $validated = $request->validate($this->ticketRules());

            $ticket->update($validated);

            // 2. Dispatch update event
            TicketUpdated::dispatch($ticket);

            return response()->json([
                'status' => 'success',
                'data' => $ticket,
                'message' => 'Ticket updated successfully.'
            ], 200);
        } catch (ValidationException $e) { 
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a ticket.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $ticket = Ticket::find($id);

            if (!$ticket) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ticket not found.'
                ], 404);
            }

            $ticket->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Ticket deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting ticket: ' . $e->getMessage()
            ], 500);
        }
    }
}